<div class="image-container">
    <img src="/storage/banners/{{$image}}" alt="Banner" class="banner" data-image="{{ $image }}"
        @if($priority) fetchpriority="high" @else loading="lazy" @endif />
    <div class="order-now">
        <img src="/storage/banners/{{$smallImage}}" alt="" class="small-img" loading="lazy" />
        <img src="/storage/banners/{{$mobileImage}}" alt="" class="mobile-img"
            @if($priority) fetchpriority="high" @else loading="lazy" @endif />
        <button class="order-btn" onclick="window.location.href='/search/products?search={{$name}}'">Check
            Game</button>
    </div>
</div>