<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Watch;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function searchItems(Request $request)
    {
        $type = $request->get('type', 'product');
        $q = trim((string) $request->get('q', ''));

        $model = $type === 'watch' ? Watch::class : Product::class;

        $query = $model::query();

        if ($q !== '') {
            $query->where('name', 'like', '%' . $q . '%');
        }

        $items = $query->orderBy('name')
            ->limit(15)
            ->get(['id', 'name', 'image', 'price', 'sale', 'stock_quantity']);

        return response()->json($items);
    }

    public function adjustBulk(Request $request, StockService $stock)
    {
        $fields = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.type' => 'required|in:product,watch',
            'items.*.id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:0',
            'items.*.threshold' => 'nullable|integer|min:0',
        ]);

        DB::transaction(function () use ($fields, $stock) {
            $items = collect($fields['items'])->sortBy(fn ($i) => $i['type'] . '-' . $i['id']);

            foreach ($items as $item) {
                $stock->setManualStock(
                    $item['type'],
                    (int) $item['id'],
                    (int) $item['quantity'],
                    array_key_exists('threshold', $item) && $item['threshold'] !== null ? (int) $item['threshold'] : null
                );
            }
        });

        return response()->json(['status' => 'saved']);
    }
}
