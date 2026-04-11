<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- SEO --}}
    <meta name="robots" content="index, follow" />
    <meta name="keywords"
        content="Hayek Gaming,Hayek Gaming Ground, Gaming store Lebanon, Playstation Lebanon,Gaming accessories Lebanon, Gaming shop Beirut, Buy PS5 Lebanon, Gaming keyboards Lebanon, Nintendo Switch Lebanon, Retro, Electronics and gadgets, Gamer Setup" />
    <meta name="author" content="Hayek Gaming Ground" />
    <meta name="copyright" content="Copyright © 2024 HayekGaming" />
    <meta name="theme-color" content="#2a2670" />
    <meta name="description"
        content="Hayek Gaming is your ultimate destination in Lebanon for gaming consoles, accessories, and gear. Discover top deals on PlayStation 5, Playstation 4, Nintendo Switch, Gaming Setups, Electronic and gadgets, Retro and more with fast delivery and expert support all over lebanon." />

    <meta property="og:title" content="Hayek Gaming Ground" />
    <meta property="og:description"
        content="Hayek Gaming is your ultimate destination in Lebanon for gaming consoles, accessories, and gear. Discover top deals on PlayStation 5, Playstation 4, Nintendo Switch, Gaming Setups, Electronic and gadgets, Retro and more with fast delivery and expert support all over lebanon." />
    <meta property="og:image" content="https://www.hayekgaming.com/img/white-logo.svg" />
    <meta property="og:url" content="https://www.hayekgaming.com" />
    <meta property="og:type" content="website" />
    {{-- End of SEO --}}
    <link rel="icon" sizes="32x32" href="/img/white-logo.svg">
    <link rel="stylesheet" href="/css/navbar.css" />
    <link rel="stylesheet" href="/css/footer.css">
    <link rel="stylesheet" href="/css/sidebar.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet" href="/css/cart.css">
  <link rel="stylesheet" href="/css/fonts.css" />

    <title>Cart</title>

</head>

<body>
    <x-navbar :categories="$categories" cartQuantity="{{$cartQuantity}}" />
    <section class="cart-page">
        <div class="cart-container">
            <h2>Shopping Cart</h2>
            @if ($products->count() > 0 || $watches->count() > 0)

            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th></th>
                        <th>Name</th>
                        <th>Quantity</th>
                        <th>Price ($)</th>
                        <th>Remove</th>
                    </tr>
                </thead>
                <tbody id="cart-body">
                    @php
                    $totalPrice = 0;
                    $rowIndex = 1;
                    @endphp

                    <form method="post" action="/checkout">
                        @csrf

                        {{-- Products --}}
                        @foreach ($products as $item)
                        @php
                        $cartItem = collect($cart)->firstWhere(fn($c) => $c['id'] == $item->id && $c['type'] ==
                        'product');
                        $qty = $cartItem['quantity'] ?? 1;
                        $price = $item->sale ?? $item->price;
                        $subtotal = $price * $qty;
                        $totalPrice += $subtotal;
                        @endphp

                        <tr data-id="{{ $item->id }}" data-type="product">
                            <input type="hidden" name="item_{{ $item->id }}" value="{{ $item->id }}">
                            <input type="hidden" name="type_{{ $item->id }}" value="product">

                            <td>{{ $rowIndex++ }}</td>
                            <td><img src="/storage/products/{{ $item->image }}" alt="{{ $item->name }}" loading="lazy">
                            </td>
                            <td>{{ $item->name }}</td>
                            <td>
                                <div class="quantity-controls">
                                    <button type="button" class="quantity-btn"
                                        onclick="updateQuantity({{ $item->id }}, 'product', -1, {{ $price }})">-</button>
                                    <input readonly type="text" class="item-quantity"
                                        id="quantity-product-{{ $item->id }}" name="quantity_{{ $item->id }}"
                                        value="{{ $qty }}">
                                    <button type="button" class="quantity-btn"
                                        onclick="updateQuantity({{ $item->id }}, 'product', 1, {{ $price }})">+</button>
                                </div>
                            </td>
                            <td id="price-product-{{ $item->id }}">{{ number_format($subtotal, 2) }}</td>
                            <td>
                                <button type="button" class="remove-btn"
                                    onclick="removeFromCart({{ $item->id }}, 'product')"
                                    title="Remove from cart">×</button>
                            </td>
                        </tr>
                        @endforeach

                        {{-- Watches --}}
                        @foreach ($watches as $item)
                        @php
                        $cartItem = collect($cart)->firstWhere(fn($c) => $c['id'] == $item->id && $c['type'] ==
                        'watch');
                        $qty = $cartItem['quantity'] ?? 1;
                        $price = $item->sale ?? $item->price;
                        $subtotal = $price * $qty;
                        $totalPrice += $subtotal;
                        @endphp

                        <tr data-id="{{ $item->id }}" data-type="watch">
                            <input type="hidden" name="item_{{ $item->id }}" value="{{ $item->id }}">
                            <input type="hidden" name="type_{{ $item->id }}" value="watch">

                            <td>{{ $rowIndex++ }}</td>
                            <td><img src="/storage/watches/{{ $item->image }}" alt="{{ $item->name }}" loading="lazy">
                            </td>
                            <td>{{ $item->name }}</td>
                            <td>
                                <div class="quantity-controls">
                                    <button type="button" class="quantity-btn"
                                        onclick="updateQuantity({{ $item->id }}, 'watch', -1, {{ $price }})">-</button>
                                    <input readonly type="text" class="item-quantity"
                                        id="quantity-watch-{{ $item->id }}" name="quantity_{{ $item->id }}"
                                        value="{{ $qty }}">
                                    <button type="button" class="quantity-btn"
                                        onclick="updateQuantity({{ $item->id }}, 'watch', 1, {{ $price }})">+</button>
                                </div>
                            </td>
                            <td id="price-watch-{{ $item->id }}">{{ number_format($subtotal, 2) }}</td>
                            <td>
                                <button type="button" class="remove-btn"
                                    onclick="removeFromCart({{ $item->id }}, 'watch')"
                                    title="Remove from cart">×</button>
                            </td>
                        </tr>
                        @endforeach
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td colspan="5">Total Price</td>
                        <td id="total-price">${{ number_format($totalPrice, 2) }}</td>
                    </tr>
                </tfoot>

            </table>
            <button class="checkout-btn" type="submit">Proceed to Checkout</button>
            </form>

            @else
            <h2 class="empty-cart">You cart is empty</h2>
            @endif

        </div>
    </section>
    <x-footer :categories="$categories" movingSentence="{{$movingSentence}}" />
    <script>
        function updateQuantity(itemId, type, change, price) {
    const qtyInput = document.getElementById(`quantity-${type}-${itemId}`);
    let itemQuantity = parseInt(qtyInput.value);

    if (isNaN(itemQuantity)) itemQuantity = 1;

    if (change === 1) itemQuantity++;
    else if (change === -1 && itemQuantity > 1) itemQuantity--;

    qtyInput.value = itemQuantity;

    const newPrice = price * itemQuantity;
    document.getElementById(`price-${type}-${itemId}`).textContent = newPrice.toFixed(2);

    updateTotalPrice();
}
function updateTotalPrice() {
    let total = 0;
    document.querySelectorAll("[id^='price-']").forEach(priceEl => {
        const value = parseFloat(priceEl.textContent);
        if (!isNaN(value)) total += value;
    });
    document.getElementById("total-price").textContent = `$${total.toFixed(2)}`;
}


    </script>
    <script src="/js/navbar.js"></script>
    <script>
        function removeFromCart(itemId) {
        fetch('/remove-from-cart', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            body: JSON.stringify({ id: itemId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                
                // Remove the row from the DOM
                const row = document.querySelector(`[data-id="${itemId}"]`);
                console.log(row);
                if (row) {
                    row.remove();
                }

                // Optionally update total price
                updateTotalPrice();

            } else {
                console.log(data.message);
            }
        })
        .catch(error => {
            console.error('Request failed:', error);
            
        });
    }

    function updateTotalPrice() {
        let total = 0;
        document.querySelectorAll("[id^='price-']").forEach(priceEl => {
            total += parseFloat(priceEl.textContent);
        });
        document.getElementById("total-price").textContent = `$${total.toFixed(2)}`;
    }
    </script>


</body>

</html>