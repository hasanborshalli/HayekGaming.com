<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link rel="icon" sizes="32x32" href="/img/white-logo.svg">
  <link rel="stylesheet" href="/css/fonts.css" />

    <link rel="stylesheet" href="/css/admin.css">
    <link rel="stylesheet" href="/css/orderDetails.css">
</head>

<body>

    <x-navigator />

    <!-- Main Content -->
    <div class="main-content">
        <!-- Order Details Section -->
        <div id="order-details">
            <h1>Order Details</h1>

            <div class="order-info">
                <h2>Customer Information</h2>
                <p><strong>Name:</strong> {{ $order->name }}</p>
                <p><strong>Mobile Number:</strong> {{ $order->mobile }}</p>
                <p><strong>Second Mobile Number:</strong> {{ $order->second_mobile }}</p>
                <p><strong>City:</strong> {{ $order->city }}</p>
                <p><strong>Street:</strong> {{ $order->street }}</p>
                <p><strong>Building:</strong> {{ $order->building }}</p>
                <p><strong>Floor:</strong> {{ $order->floor }}</p>
                <p><strong>Remarks:</strong> {{ $order->remarks ?? 'No remarks' }}</p>
            </div>

            <div class="order-summary">
                <h2>Order Summary</h2>
                <p><strong>Total Price:</strong> ${{ number_format($order->total, 2) }}</p>
                <p><strong>Ordered on:</strong> {{ $order->created_at->format('Y-m-d H:i:s') }}</p>
            </div>

            <div class="order-items">
                <h2>Order Items</h2>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Product Category</th>
                                <th>Quantity</th>
                                <th>Price per Unit</th>
                                <th>Total Price</th>
                                <th>Product Image</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderItems as $item)
                            @php
                            $isProduct = !empty($item->product_id);
                            $object = $isProduct
                            ? \App\Models\Product::find($item->product_id)
                            : \App\Models\Watch::find($item->watch_id);

                            $name = $object->name ?? 'Unknown';
                            $categoryOrType = $isProduct
                            ? ($object->category->name ?? 'N/A')
                            : ($object->type->name ?? 'N/A');
                            $price = $object->sale ?? $object->price ?? 0;
                            $subtotal = $item->quantity * $price;
                            $imagePath = $isProduct
                            ? "/storage/products/{$object->image}"
                            : "/storage/watches/{$object->image}";
                            @endphp

                            <tr>
                                <td>{{ $name }}</td>
                                <td>{{ $categoryOrType }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>${{ number_format($price, 2) }}</td>
                                <td>${{ number_format($subtotal, 2) }}</td>
                                <td><img src="{{ $imagePath }}" alt="Item Image"
                                        style="max-width: 100px; height: auto;"></td>
                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
            <a href="/finishOrder/{{$order->id}}"> <button id="mark-done-btn">Mark as Done</button></a>
        </div>
    </div>

</body>

</html>