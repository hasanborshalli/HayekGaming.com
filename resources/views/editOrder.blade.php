<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" sizes="32x32" href="/img/white-logo.svg">
  <link rel="stylesheet" href="/css/fonts.css" />

    <title>Edit Order</title>
    <link rel="stylesheet" href="/css/admin.css">
    <link rel="stylesheet" href="/css/editOrder.css">
</head>

<body>

    <x-navigator />
    <section class="addProduct-page">
        <h1 class="page-title">Edit Order</h1>

        <form action="/order/edit/{{$order->id}}" method="POST">
            @csrf

            <div class="form-group">
                <label for="customer-name">Customer Name</label>
                <input type="text" id="customer-name" name="name" value="{{ old('name', $order->name) }}" required>
                @error('name')
                <p style="color:red">{{$message}}</p>
                @enderror
            </div>

            <!-- Customer Mobile -->
            <div class="form-group">
                <label for="customer-mobile">Mobile Number</label>
                <input type="text" id="customer-mobile" name="mobile" value="{{ old('mobile', $order->mobile) }}"
                    required>
                @error('mobile')
                <p style="color:red">{{$message}}</p>
                @enderror
            </div>

            <!-- Customer Address -->
            <div class="form-group">
                <label for="customer-address">City</label>
                <input type="text" id="customer-address" name="city" value="{{ old('city', $order->city) }}" required>
                @error('city')
                <p style="color:red">{{$message}}</p>
                @enderror
            </div>
            <div class="form-group">
                <label for="customer-address">Street</label>
                <input type="text" id="customer-address" name="street" value="{{ old('street', $order->street) }}">
                @error('street')
                <p style="color:red">{{$message}}</p>
                @enderror
            </div>
            <div class="form-group">
                <label for="customer-address">Building</label>
                <input type="text" id="customer-address" name="building"
                    value="{{ old('building', $order->building) }}">
                @error('building')
                <p style="color:red">{{$message}}</p>
                @enderror
            </div>
            <div class="form-group">
                <label for="customer-address">Floor</label>
                <input type="text" id="customer-address" name="floor" value="{{ old('floor', $order->floor) }}">
                @error('floor')
                <p style="color:red">{{$message}}</p>
                @enderror
            </div>

            <!-- Remarks -->
            <div class="form-group">
                <label for="remarks">Remarks</label>
                <textarea id="remarks" name="remarks">{{ old('remarks', $order->remarks) }}</textarea>
                @error('remarks')
                <p style="color:red">{{$message}}</p>
                @enderror
            </div>

            <!-- Order Items -->
            <br>
            <hr>
            <br>
            <div class="form-group">
                <label for="order-items">Order Items</label>
                <div class="order-items">
                    @foreach ($order->orderItems as $index => $item)
                    @php
                    $isProduct = !empty($item->product_id);
                    $object = $isProduct
                    ? \App\Models\Product::find($item->product_id)
                    : \App\Models\Watch::find($item->watch_id);

                    $name = $object->name ?? 'Unknown';
                    $price = $object->sale ?? $object->price ?? 0;
                    $quantity = $item->quantity ?? 1;
                    $subtotal = $price * $quantity;
                    $type = $isProduct ? 'product' : 'watch';
                    @endphp

                    <div class="order-item">
                        <p class="input-label"><strong>{{ $name }}</strong> ({{ ucfirst($type) }})</p>

                        <div class="quantity-controls">
                            <input type="number" class="item-quantity" name="quantity_{{ $type }}_{{ $object->id }}"
                                value="{{ $quantity }}" id="quantity-{{ $type }}-{{ $object->id }}" min="0"
                                data-price="{{ $price }}" data-id="{{ $object->id }}" data-type="{{ $type }}" />
                        </div>

                        <input type="hidden" name="item_{{ $type }}_{{ $object->id }}" value="{{ $object->id }}">
                        <p id="item-price-{{ $type }}-{{ $object->id }}">Price: ${{ number_format($subtotal, 2) }}</p>
                    </div>
                    @endforeach
                </div>

            </div>

            <!-- Total Order Price -->
            <div class="form-group">
                <label for="total-price">Total Order Price</label>
                <input type="number" readonly id="total-price" name="total" value="{{ old('total', $order->total) }}"
                    readonly>
                @error('total')
                <p style="color:red">{{$message}}</p>
                @enderror
            </div>
            <div class="form-group">
                <button type="submit" class="submit-btn">Save Changes</button>
            </div>
        </form>
    </section>
    <script>
        function updateTotalPrice() {
    let totalPrice = 0;

    // Loop through all items and update their price
    document.querySelectorAll('.item-quantity').forEach(function(input) {
        const quantity = parseInt(input.value) || 0;
        const pricePerItem = parseFloat(input.getAttribute('data-price')) || 0;
        const itemId = input.getAttribute('data-id');
        const type = input.getAttribute('data-type');
        const itemTotalPrice = quantity * pricePerItem;

        // Update item price display
        document.getElementById('item-price-' + type + '-' + itemId).innerText = "Price: $" + itemTotalPrice.toFixed(2);

        // Add the item price to total
        totalPrice += itemTotalPrice;
    });

    // Update total
    document.getElementById('total-price').value = totalPrice.toFixed(2);
}

// Add listener for quantity change
document.querySelectorAll('.item-quantity').forEach(function(input) {
    input.addEventListener('input', updateTotalPrice);
});

// Initial total calc
updateTotalPrice();
    </script>

</body>

</html>