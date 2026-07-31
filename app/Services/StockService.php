<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Watch;
use Illuminate\Validation\ValidationException;

class StockService
{
    protected function modelClass(string $type): string
    {
        return $type === 'watch' ? Watch::class : Product::class;
    }

    /**
     * Must be called inside a DB::transaction() — row-locks the product/watch.
     */
    protected function lockItem(string $type, int $id)
    {
        $item = $this->modelClass($type)::lockForUpdate()->find($id);

        if (!$item) {
            throw ValidationException::withMessages([
                'items' => 'One of the selected items no longer exists.',
            ]);
        }

        return $item;
    }

    public function applyPurchaseLine(string $type, int $id, int $qty, float $unitPrice): void
    {
        $item = $this->lockItem($type, $id);

        $existingQty = $item->stock_quantity ?? 0;
        $existingAvgCost = $item->avg_cost ?? 0;
        $wasTrackedAtZero = $item->stock_quantity !== null && $existingQty === 0;

        $costPool = ($existingQty * $existingAvgCost) + ($qty * $unitPrice);
        $newQty = $existingQty + $qty;

        $item->stock_quantity = $newQty;
        $item->avg_cost = $newQty > 0 ? round($costPool / $newQty, 2) : null;

        if ($wasTrackedAtZero && $newQty > 0) {
            $item->is_available = true;
        }

        $item->save();
    }

    /**
     * Used for purchase-line edit (and delete, with $newQty = 0).
     * Reverses the old line's contribution to quantity/cost pool and applies the new one.
     */
    public function reversePurchaseLine(string $type, int $id, int $oldQty, float $oldUnitPrice, int $newQty, float $newUnitPrice): void
    {
        $item = $this->lockItem($type, $id);

        $currentQty = $item->stock_quantity ?? 0;
        $currentAvgCost = $item->avg_cost ?? 0;
        $wasTrackedAtZero = $item->stock_quantity !== null && $currentQty === 0;

        $costPool = ($currentQty * $currentAvgCost) - ($oldQty * $oldUnitPrice) + ($newQty * $newUnitPrice);
        $resultQty = $currentQty - $oldQty + $newQty;

        if ($resultQty < 0) {
            throw ValidationException::withMessages([
                'items' => "Cannot save this change for \"{$item->name}\": it would result in negative stock (some of this stock has already been sold or punched out).",
            ]);
        }

        $item->stock_quantity = $resultQty;
        $item->avg_cost = $resultQty > 0 ? round($costPool / $resultQty, 2) : null;

        if ($resultQty === 0) {
            $item->is_available = false;
        } elseif ($wasTrackedAtZero && $resultQty > 0) {
            $item->is_available = true;
        }

        $item->save();
    }

    /**
     * Used for a sale (punch-order line, website checkout, order-quantity increase).
     * No-ops for untracked items.
     */
    public function decreaseStock(string $type, int $id, int $qty): void
    {
        $item = $this->lockItem($type, $id);

        if ($item->stock_quantity === null) {
            return;
        }

        if ($item->stock_quantity < $qty) {
            throw ValidationException::withMessages([
                'items' => "Not enough stock for \"{$item->name}\": only {$item->stock_quantity} available.",
            ]);
        }

        $item->stock_quantity -= $qty;

        if ($item->stock_quantity === 0) {
            $item->is_available = false;
        }

        $item->save();
    }

    /**
     * Reverses a sale (punch-order/website-order edit or delete). No-ops for untracked items.
     */
    public function increaseStockPlain(string $type, int $id, int $qty): void
    {
        $item = $this->lockItem($type, $id);

        if ($item->stock_quantity === null) {
            return;
        }

        $wasZero = $item->stock_quantity === 0;
        $item->stock_quantity += $qty;

        if ($wasZero && $item->stock_quantity > 0) {
            $item->is_available = true;
        }

        $item->save();
    }

    /**
     * Manual correction / stocktake from the Stock page. Does not touch avg_cost.
     * This is also how an untracked item becomes tracked without a purchase.
     */
    public function setManualStock(string $type, int $id, ?int $quantity, ?int $threshold): void
    {
        $item = $this->lockItem($type, $id);

        $wasTrackedAtZero = $item->stock_quantity !== null && $item->stock_quantity === 0;

        if ($threshold !== null) {
            $item->stock_threshold = $threshold;
        }

        if ($quantity !== null) {
            $item->stock_quantity = $quantity;

            if ($quantity === 0) {
                $item->is_available = false;
            } elseif ($quantity > 0 && $wasTrackedAtZero) {
                $item->is_available = true;
            }
        }

        $item->save();
    }
}
