<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PunchOrder;
use App\Models\PunchOrderItem;
use App\Models\Watch;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PunchOrderController extends Controller
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
            'items.*.punch_order_item_id' => 'nullable|integer',
            'items.*.type' => 'required|in:product,watch',
            'items.*.id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
        ]);
    }

    protected function currentUnitPrice(string $type, int $id): float
    {
        $model = $type === 'watch' ? Watch::class : Product::class;
        $item = $model::findOrFail($id);

        return (float) ($item->sale ?? $item->price);
    }

    public function store(Request $request)
    {
        $fields = $this->validateItems($request);
        $items = collect($fields['items'])->sortBy(fn ($i) => $i['type'] . '-' . $i['id'])->values();

        try {
            DB::transaction(function () use ($fields, $items) {
                $punchOrder = PunchOrder::create([
                    'note' => $fields['note'] ?? null,
                    'total' => 0,
                ]);

                $total = 0;

                foreach ($items as $item) {
                    $this->stock->decreaseStock($item['type'], (int) $item['id'], (int) $item['quantity']);
                    $unitPrice = $this->currentUnitPrice($item['type'], (int) $item['id']);
                    $total += $unitPrice * $item['quantity'];

                    PunchOrderItem::create([
                        'punch_order_id' => $punchOrder->id,
                        'product_id' => $item['type'] === 'product' ? $item['id'] : null,
                        'watch_id' => $item['type'] === 'watch' ? $item['id'] : null,
                        'type' => $item['type'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $unitPrice,
                    ]);
                }

                $punchOrder->update(['total' => $total]);
            });
        } catch (ValidationException $e) {
            return redirect()->back()->withInput()->withErrors($e->errors());
        }

        return redirect('/admin/stock/punch-orders')->with('message', 'Order punched successfully');
    }

    public function update(Request $request, PunchOrder $punchOrder)
    {
        $fields = $this->validateItems($request);

        try {
            DB::transaction(function () use ($fields, $punchOrder) {
                $existingItems = $punchOrder->items()->get()->keyBy('id');
                $submittedIds = collect($fields['items'])->pluck('punch_order_item_id')->filter()->all();

                foreach ($existingItems as $existing) {
                    if (!in_array($existing->id, $submittedIds)) {
                        $existingItemId = $existing->product_id ?? $existing->watch_id;
                        $this->stock->increaseStockPlain($existing->type, $existingItemId, $existing->quantity);
                        $existing->delete();
                    }
                }

                $total = 0;

                foreach ($fields['items'] as $line) {
                    $matchedExisting = !empty($line['punch_order_item_id']) ? $existingItems->get($line['punch_order_item_id']) : null;

                    if ($matchedExisting) {
                        $oldItemId = $matchedExisting->product_id ?? $matchedExisting->watch_id;
                        $itemChanged = $matchedExisting->type !== $line['type'] || (int) $oldItemId !== (int) $line['id'];

                        if ($itemChanged) {
                            $this->stock->increaseStockPlain($matchedExisting->type, $oldItemId, $matchedExisting->quantity);
                            $this->stock->decreaseStock($line['type'], (int) $line['id'], (int) $line['quantity']);
                            $unitPrice = $this->currentUnitPrice($line['type'], (int) $line['id']);
                        } else {
                            $delta = (int) $line['quantity'] - $matchedExisting->quantity;
                            if ($delta > 0) {
                                $this->stock->decreaseStock($line['type'], (int) $line['id'], $delta);
                            } elseif ($delta < 0) {
                                $this->stock->increaseStockPlain($line['type'], (int) $line['id'], -$delta);
                            }
                            $unitPrice = $matchedExisting->unit_price;
                        }

                        $total += $unitPrice * $line['quantity'];

                        $matchedExisting->update([
                            'product_id' => $line['type'] === 'product' ? $line['id'] : null,
                            'watch_id' => $line['type'] === 'watch' ? $line['id'] : null,
                            'type' => $line['type'],
                            'quantity' => $line['quantity'],
                            'unit_price' => $unitPrice,
                        ]);
                    } else {
                        $this->stock->decreaseStock($line['type'], (int) $line['id'], (int) $line['quantity']);
                        $unitPrice = $this->currentUnitPrice($line['type'], (int) $line['id']);
                        $total += $unitPrice * $line['quantity'];

                        PunchOrderItem::create([
                            'punch_order_id' => $punchOrder->id,
                            'product_id' => $line['type'] === 'product' ? $line['id'] : null,
                            'watch_id' => $line['type'] === 'watch' ? $line['id'] : null,
                            'type' => $line['type'],
                            'quantity' => $line['quantity'],
                            'unit_price' => $unitPrice,
                        ]);
                    }
                }

                $punchOrder->update(['total' => $total]);
            });
        } catch (ValidationException $e) {
            return redirect()->back()->withInput()->withErrors($e->errors());
        }

        return redirect('/admin/stock/punch-orders')->with('message', 'Punch order updated successfully');
    }

    public function destroy(PunchOrder $punchOrder)
    {
        try {
            DB::transaction(function () use ($punchOrder) {
                foreach ($punchOrder->items as $item) {
                    $itemId = $item->product_id ?? $item->watch_id;
                    $this->stock->increaseStockPlain($item->type, $itemId, $item->quantity);
                }
                $punchOrder->delete();
            });
        } catch (ValidationException $e) {
            return response()->json(['status' => 'failed', 'message' => $e->getMessage()]);
        }

        return response()->json(['status' => 'removed']);
    }
}
