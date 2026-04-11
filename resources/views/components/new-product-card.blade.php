<div class="product-card {{$salePrice ? 'sales' : ''}} {{$isAvailable ? '' : 'notAvailable'}}">
    <div class="card-image position-relative">
        <a href="/product/{{$id}}" class="image-wrapper {{ !$isAvailable ? 'unavailable' : '' }}">
            <img src="/storage/products/{{$image}}" alt="{{ html_entity_decode($title) }}" loading="lazy">
        </a>

        @if($salePrice && $isAvailable)
        <div class="sale-badge">SALE</div>
        @endif

        @unless($isAvailable)
        <div class="sold-out-overlay">SOLD OUT</div>
        @endunless
    </div>


    <div class="card-details">
        <div class="card-category-price">
            <span class="product-category">{{ html_entity_decode($category) }}</span>
            @if($salePrice)
            <div class="product-price">
                <span class="old-price">${{ number_format($price, 2) }}</span>
                <span class="sale-price">${{ number_format($salePrice, 2) }}</span>
            </div>
            @else
            <div class="product-price">
                <span>${{ number_format($price, 2) }}</span>
            </div>
            @endif
        </div>

        <div class="product-title">
            <p>{{ html_entity_decode($title) }}</p>
        </div>

        @if($isAvailable)
        <button class="add-to-cart-btn {{$salePrice ? 'sales-add-to-cart-btn' : ''}}"
            onclick="addToCart({{ $id }},'product')">
            Add to cart
            <img src="/img/{{ ($forceColoredCart ?? false) ? 'colored-' : ($salePrice ? '' : 'colored-') }}cart.svg"
                class="cart-icon" />

        </button>
        @else
        <button class="add-to-cart-btn sold-out-btn " disabled>
            Sold Out
        </button>
        @endif
    </div>
</div>