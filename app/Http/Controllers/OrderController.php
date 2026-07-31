<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Services\StockService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\Watch;

class OrderController extends Controller
{
    protected StockService $stock;

    public function __construct(StockService $stock)
    {
        $this->stock = $stock;
    }

    public function addCart(Request $request, $check = false)
{
    $fields = $request->validate([
        'id' => ['required', 'integer'],
        'quantity' => ['required', 'integer', 'min:1'],
        'type' => ['required', 'in:product,watch'],
    ]);

    $itemId = $fields['id'];
    $quantity = $fields['quantity'];
    $type = $fields['type'];

    // validate that it exists in the correct table (and fetch stock info while we're at it)
    $item = $type === 'watch' ? Watch::find($itemId) : Product::find($itemId);

    if (!$item) {
        return response()->json(['status' => 'invalid']);
    }

    $cart = session('cart_items', []);
    $itemFound = false;
    $existingQuantity = 0;

    foreach ($cart as $cartItem) {
        if ($cartItem['id'] == $itemId && $cartItem['type'] == $type) {
            $existingQuantity = $cartItem['quantity'];
        }
    }

    if ($item->stock_quantity !== null && ($existingQuantity + $quantity) > $item->stock_quantity) {
        $message = $item->stock_quantity > 0
            ? "Only {$item->stock_quantity} of \"{$item->name}\" left in stock."
            : "\"{$item->name}\" is currently out of stock.";

        if ($check) {
            return redirect()->back()->with('error', $message);
        }

        return response()->json([
            'status' => 'insufficientStock',
            'message' => $message,
            'available' => max(0, $item->stock_quantity - $existingQuantity),
        ]);
    }

    foreach ($cart as $key => $cartItem) {
        if ($cartItem['id'] == $itemId && $cartItem['type'] == $type) {
            $itemFound = true;
            $cart[$key]['quantity'] += $quantity;
        }
    }

    if (!$itemFound) {
        $cart[] = [
            'id' => $itemId,
            'type' => $type, // 👈 NEW
            'quantity' => $quantity,
        ];
    }

    session(['cart_items' => $cart]);

    if ($check) {
        $orderedItems[] = [
            'item_id' => $itemId,
            'type' => $type,
            'quantity' => $quantity,
        ];
        session()->put('ordered_items', $orderedItems);
        return redirect('/checkout');
    } else {
        return response()->json([
            'status' => $itemFound ? 'addedOld' : 'addedNew',
        ]);
    }
}


public function remove(Request $request)
{
    $id = $request->input('id');

    if (!$id) {
        return response()->json(['status' => 'error', 'message' => 'Missing product ID'], 400);
    }

    $cart = session()->get('cart_items', []);

    // Check if the item exists
    $exists = false;
    $newCart = [];

    $itemFound=false;
        foreach ($cart as  $item) {
            if ($item['id'] == $id) {
                $itemFound = true;
                continue;
            }
             $newCart[] = $item;
        }
        if(!$itemFound) {
        return response()->json(['status' => 'error', 'message' => 'Item not found in cart'], 404);
        }


    // Update session
    session(['cart_items' => $newCart]);

    return response()->json(['status' => 'success', 'message' => 'Item removed']);
}



    public function checkout(Request $request)
{
    $orderedItems = [];

    foreach ($request->all() as $key => $value) {
        if (strpos($key, 'item_') === 0) {
            $itemId = $value;
            $quantity = $request->input('quantity_' . $itemId);
            $type = $request->input('type_' . $itemId, 'product'); // default to 'product'

            $orderedItems[] = [
                'item_id' => $itemId,
                'type' => $type,
                'quantity' => $quantity,
            ];
        }
    }

    session()->put('ordered_items', $orderedItems);

    return redirect('/checkout');
}


public function order(Request $request)
{
    // 🧾 Validate customer info
    $fields = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'mobile' => ['required', 'digits:8'],
        'second_mobile' => ['nullable', 'digits:8'],
        'city' => ['required', 'string', 'max:255'],
        'street' => ['required', 'string', 'max:255'],
        'building' => ['nullable', 'string', 'max:255'],
        'floor' => ['nullable', 'string', 'max:255'],
        'remarks' => ['nullable', 'string'],
    ]);

    $items = session()->get('ordered_items', []);
    $totalPrice = 0;

    if (empty($items)) {
        return redirect('/cart')->with('error', 'Your cart is empty.');
    }

    // 🧮 Calculate total
    foreach ($items as $orderedItem) {
        $itemId = $orderedItem['item_id'];
        $quantity = (int) $orderedItem['quantity'];
        $type = $orderedItem['type'] ?? 'product';

        // Fetch item depending on type
        $item = $type === 'watch'
            ? Watch::find($itemId)
            : Product::find($itemId);

        if ($item && $quantity > 0) {
            $itemPrice = $item->sale ?? $item->price;
            $totalPrice += $itemPrice * $quantity;
        }
    }

    // 🧾 Prevent empty or invalid orders
    if ($totalPrice <= 0) {
        return redirect('/cart')->with('error', 'Invalid order total.');
    }

    // 💾 Create order and items atomically
    try {
        $order = DB::transaction(function () use ($fields, $totalPrice, $items) {
            $fields['total'] = $totalPrice;
            $order = Order::create($fields);

            // 💿 Insert items into order_items
            foreach ($items as $orderedItem) {
                $itemId = (int) $orderedItem['item_id'];
                $quantity = (int) $orderedItem['quantity'];
                $type = $orderedItem['type'] ?? 'product';

                // 📉 Re-validate and decrement stock at the moment of purchase (closes the race
                // between add-to-cart and checkout)
                $this->stock->decreaseStock($type, $itemId, $quantity);

                DB::table('order_items')->insert([
                    'order_id' => $order->id,
                    'product_id' => $type === 'product' ? $itemId : null,
                    'watch_id' => $type === 'watch' ? $itemId : null,
                    'quantity' => $quantity,
                    'type' => $type,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            return $order;
        });
    } catch (ValidationException $e) {
        $message = collect($e->errors())->flatten()->first() ?? 'One of the items in your cart is no longer available.';
        return redirect('/cart')->with('error', $message);
    }

    // 🧹 Clear cart/session
    session()->forget('ordered_items');
    session()->forget('cart_items');

    // ✅ Redirect to thank-you page
    return redirect('thankyou?orderNumber=' . $order->id);
}


    public function DeleteOrder(order $order)
    {
        DB::transaction(function () use ($order) {
            $items = DB::table('order_items')->where('order_id', $order->id)->get();
            foreach ($items as $item) {
                $type = $item->type ?? 'product';
                $itemId = $item->product_id ?? $item->watch_id;
                if ($itemId) {
                    $this->stock->increaseStockPlain($type, (int) $itemId, (int) $item->quantity);
                }
            }
            $order->delete();
        });

        return response()->json(['status'=>"removed"]);
    }
    public function FinishOrder(order $order)
    {
        $order->done=true;
        $order->save();
        return redirect('/admin/orders');
    }
    public function EditOrder(Request $request, Order $order)
{
    $fields = $request->validate([
        'name' => ['string', 'max:255'],
        'mobile' => ['string'],
        'second_mobile' => ['nullable', 'string'],
        'city' => ['string', 'max:255'],
        'street' => ['string', 'max:255'],
        'building' => ['nullable', 'string', 'max:255'],
        'floor' => ['nullable', 'string', 'max:255'],
        'remarks' => ['nullable', 'string'],
    ]);

    // ✅ Update basic order info
    $order->update($fields);

    $totalPrice = 0;

    try {
        DB::transaction(function () use ($request, $order, &$totalPrice) {
            // ✅ Loop through all request items
            foreach ($request->all() as $key => $value) {
                // 🟢 Product items
                if (str_starts_with($key, 'item_product_')) {
                    $productId = str_replace('item_product_', '', $key);
                    $quantity = (int) $request->input('quantity_product_' . $productId);

                    $product = \App\Models\Product::find($productId);
                    if ($product && $quantity >= 0) {
                        $currentRow = DB::table('order_items')
                            ->where('order_id', $order->id)
                            ->where('product_id', $productId)
                            ->first();
                        $oldQuantity = $currentRow->quantity ?? 0;
                        $delta = $quantity - $oldQuantity;

                        if ($delta > 0) {
                            $this->stock->decreaseStock('product', (int) $productId, $delta);
                        } elseif ($delta < 0) {
                            $this->stock->increaseStockPlain('product', (int) $productId, -$delta);
                        }

                        DB::table('order_items')
                            ->where('order_id', $order->id)
                            ->where('product_id', $productId)
                            ->update([
                                'quantity' => $quantity,
                                'updated_at' => now(),
                            ]);

                        $price = $product->sale ?? $product->price;
                        $totalPrice += $price * $quantity;
                    }
                }

                // 🟣 Watch items
                elseif (str_starts_with($key, 'item_watch_')) {
                    $watchId = str_replace('item_watch_', '', $key);
                    $quantity = (int) $request->input('quantity_watch_' . $watchId);

                    $watch = \App\Models\Watch::find($watchId);
                    if ($watch && $quantity >= 0) {
                        $currentRow = DB::table('order_items')
                            ->where('order_id', $order->id)
                            ->where('watch_id', $watchId)
                            ->first();
                        $oldQuantity = $currentRow->quantity ?? 0;
                        $delta = $quantity - $oldQuantity;

                        if ($delta > 0) {
                            $this->stock->decreaseStock('watch', (int) $watchId, $delta);
                        } elseif ($delta < 0) {
                            $this->stock->increaseStockPlain('watch', (int) $watchId, -$delta);
                        }

                        DB::table('order_items')
                            ->where('order_id', $order->id)
                            ->where('watch_id', $watchId)
                            ->update([
                                'quantity' => $quantity,
                                'updated_at' => now(),
                            ]);

                        $price = $watch->sale ?? $watch->price;
                        $totalPrice += $price * $quantity;
                    }
                }
            }
        });
    } catch (ValidationException $e) {
        $message = collect($e->errors())->flatten()->first() ?? 'Could not update this order.';
        return redirect('/order/edit/' . $order->id)->with('error', $message);
    }

    // ✅ Update total price
    $order->total = $totalPrice;
    $order->save();

    return redirect('/admin/orders')->with('message', 'Order Edited Successfully');
}

}