<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PurchaseController extends Controller
{
    protected StockService $stock;

    public function __construct(StockService $stock)
    {
        $this->stock = $stock;
    }

    protected function validateItems(Request $request): array
    {
        return $request->validate([
            'note' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.purchase_item_id' => 'nullable|integer',
            'items.*.type' => 'required|in:product,watch',
            'items.*.id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);
    }

    public function store(Request $request)
    {
        $fields = $this->validateItems($request);

        $items = collect($fields['items'])->sortBy(fn ($i) => $i['type'] . '-' . $i['id'])->values();

        try {
            DB::transaction(function () use ($fields, $items) {
                $total = $items->sum(fn ($i) => $i['quantity'] * $i['unit_price']);

                $purchase = Purchase::create([
                    'note' => $fields['note'] ?? null,
                    'total' => $total,
                ]);

                foreach ($items as $item) {
                    PurchaseItem::create([
                        'purchase_id' => $purchase->id,
                        'product_id' => $item['type'] === 'product' ? $item['id'] : null,
                        'watch_id' => $item['type'] === 'watch' ? $item['id'] : null,
                        'type' => $item['type'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                    ]);

                    $this->stock->applyPurchaseLine($item['type'], (int) $item['id'], (int) $item['quantity'], (float) $item['unit_price']);
                }
            });
        } catch (ValidationException $e) {
            return redirect()->back()->withInput()->withErrors($e->errors());
        }

        return redirect('/admin/stock/purchases')->with('message', 'Purchase recorded successfully');
    }

    public function update(Request $request, Purchase $purchase)
    {
        $fields = $this->validateItems($request);

        try {
            DB::transaction(function () use ($fields, $purchase) {
                $existingItems = $purchase->items()->get()->keyBy('id');
                $submittedIds = collect($fields['items'])->pluck('purchase_item_id')->filter()->all();

                // Remove lines that were dropped from the form
                foreach ($existingItems as $existing) {
                    if (!in_array($existing->id, $submittedIds)) {
                        $existingItemId = $existing->product_id ?? $existing->watch_id;
                        $this->stock->reversePurchaseLine($existing->type, $existingItemId, $existing->quantity, $existing->unit_price, 0, 0);
                        $existing->delete();
                    }
                }

                $total = 0;

                foreach ($fields['items'] as $line) {
                    $total += $line['quantity'] * $line['unit_price'];
                    $matchedExisting = !empty($line['purchase_item_id']) ? $existingItems->get($line['purchase_item_id']) : null;

                    if ($matchedExisting) {
                        $oldItemId = $matchedExisting->product_id ?? $matchedExisting->watch_id;
                        $itemChanged = $matchedExisting->type !== $line['type'] || (int) $oldItemId !== (int) $line['id'];

                        if ($itemChanged) {
                            $this->stock->reversePurchaseLine($matchedExisting->type, $oldItemId, $matchedExisting->quantity, $matchedExisting->unit_price, 0, 0);
                            $this->stock->applyPurchaseLine($line['type'], (int) $line['id'], (int) $line['quantity'], (float) $line['unit_price']);
                        } else {
                            $this->stock->reversePurchaseLine($line['type'], (int) $line['id'], $matchedExisting->quantity, $matchedExisting->unit_price, (int) $line['quantity'], (float) $line['unit_price']);
                        }

                        $matchedExisting->update([
                            'product_id' => $line['type'] === 'product' ? $line['id'] : null,
                            'watch_id' => $line['type'] === 'watch' ? $line['id'] : null,
                            'type' => $line['type'],
                            'quantity' => $line['quantity'],
                            'unit_price' => $line['unit_price'],
                        ]);
                    } else {
                        $this->stock->applyPurchaseLine($line['type'], (int) $line['id'], (int) $line['quantity'], (float) $line['unit_price']);

                        PurchaseItem::create([
                            'purchase_id' => $purchase->id,
                            'product_id' => $line['type'] === 'product' ? $line['id'] : null,
                            'watch_id' => $line['type'] === 'watch' ? $line['id'] : null,
                            'type' => $line['type'],
                            'quantity' => $line['quantity'],
                            'unit_price' => $line['unit_price'],
                        ]);
                    }
                }

                $purchase->update([
                    'note' => $fields['note'] ?? null,
                    'total' => $total,
                ]);
            });
        } catch (ValidationException $e) {
            return redirect()->back()->withInput()->withErrors($e->errors());
        }

        return redirect('/admin/stock/purchases')->with('message', 'Purchase updated successfully');
    }

    public function destroy(Purchase $purchase)
    {
        try {
            DB::transaction(function () use ($purchase) {
                foreach ($purchase->items as $item) {
                    $itemId = $item->product_id ?? $item->watch_id;
                    $this->stock->reversePurchaseLine($item->type, $itemId, $item->quantity, $item->unit_price, 0, 0);
                }
                $purchase->delete();
            });
        } catch (ValidationException $e) {
            return response()->json(['status' => 'failed', 'message' => $e->getMessage()]);
        }

        return response()->json(['status' => 'removed']);
    }
}
