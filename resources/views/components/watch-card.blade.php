<div class="product-card {{ $salePrice ? 'sales' : '' }} {{ $isAvailable ? '' : 'notAvailable' }}">
    <div class="card-image position-relative">
        <a href="/watch/{{$id}}" class="image-wrapper {{ !$isAvailable ? 'unavailable' : '' }}">
            <div class="watch-slider" data-card-id="watch-{{ $id }}">
                <div class="watch-track">
                    @php
                    $slides = [];
                    if(!empty($image)) $slides[] = $image;
                    if(!empty($image1)) $slides[] = $image1;
                    if(!empty($image2)) $slides[] = $image2;
                    if(!empty($image3)) $slides[] = $image3;
                    if(!empty($image4)) $slides[] = $image4;
                    if(!empty($image5)) $slides[] = $image5;
                    if(!empty($image6)) $slides[] = $image6;
                    @endphp

                    @foreach($slides as $src)
                    <img class="watch-slide" src="/storage/watches/{{ $src }}" alt="{{ html_entity_decode($title) }}"
                        loading="lazy">
                    @endforeach
                </div>
            </div>
        </a>

        @if($salePrice && $isAvailable)
        <div class="sale-badge">SALE</div>
        @endif

        @unless($isAvailable)
        <div class="sold-out-overlay">SOLD OUT</div>
        @endunless
    </div>

    @php
    $colorList = [
    ['hex' => $color ?? null, 'img' => $image ?? null],
    ['hex' => $color1 ?? null, 'img' => $image1 ?? null],
    ['hex' => $color2 ?? null, 'img' => $image2 ?? null],
    ['hex' => $color3 ?? null, 'img' => $image3 ?? null],
    ['hex' => $color4 ?? null, 'img' => $image4 ?? null],
    ['hex' => $color5 ?? null, 'img' => $image5 ?? null],
    ['hex' => $color6 ?? null, 'img' => $image6 ?? null],
    ];
    $colorList = array_values(array_filter($colorList, fn($i) => !empty($i['hex']) && !empty($i['img'])));
    @endphp

    @if(count($colorList) > 0)
    <div class="color-dots-container" data-card-id="watch-{{ $id }}">
        @foreach($colorList as $i => $item)
        <span class="color-dot {{ $i === 0 ? 'active-dot' : '' }}" style="background-color: {{ $item['hex'] }};"
            data-index="{{ $i }}" title="{{ $item['hex'] }}"></span>
        @endforeach
    </div>
    @endif


    <div class="card-details">
        <div class="card-category-price">
            <span class="product-category">{{ html_entity_decode($type) }}</span>
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
        <button class="add-to-cart-btn {{ $salePrice ? 'sales-add-to-cart-btn' : '' }}"
            onclick="addToCart({{ $id }},'watch')">
            Add to cart
            <img src="/img/black-cart.svg" class="cart-icon" />
        </button>
        @else
        <button class="add-to-cart-btn sold-out-btn" disabled>Sold Out</button>
        @endif
    </div>
</div>