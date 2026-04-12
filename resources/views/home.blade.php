<!DOCTYPE html>
<html lang="en">

<head>
	<meta name="format-detection" content="telephone=no, date=no, address=no, email=no">

	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta http-equiv="X-UA-Compatible" content="ie=edge" />

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
	<link rel="stylesheet" href="/css/home.css" />
	<link rel="stylesheet" href="/css/carousel.css">
	<link rel="stylesheet" href="/css/footer.css">
	<link rel="stylesheet" href="/css/sidebar.css">
	<link rel="stylesheet" href="/css/productsList.css">
	<link rel="stylesheet" href="/css/toast.css">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
	<link rel="stylesheet" href="/css/fonts.css" />

	<title>Hayek Gaming Ground</title>
</head>

<body>
	<x-navbar :categories="$categories" cartQuantity="{{$cartQuantity}}" />
	<div class="whole-carousel">
		<div class="carousel-left">
			@php
			$prevIndex = ($activeIndex - 1 + $banners->count()) % $banners->count();
			$prevBanner = $banners[$prevIndex];
			@endphp
			<img src="/storage/banners/{{$prevBanner->image}}" alt="" id="prevBanner">
		</div>
		<section class="carousel-wrapper">
			<div class="carousel-container">
				<div class="carousel-track">
					@foreach ($banners as $banner)
					<x-image-container image="{{ $banner->image }}" mobile-image="{{ $banner->mobile_image }}"
						small-image="{{ $banner->small_image }}" id="{{ $banner->product->id }}"
						name="{{$banner->product->name}}" />
					@endforeach
				</div>

			</div>
			<div class="carousel-dots">
				<span class="dot active"></span>
				@for ($i=0;$i<$banners->count()-1;$i++)
					<span class="dot"></span>
					@endfor
			</div>
		</section>
		<div class="carousel-right">
			@php
			$nextIndex = ($activeIndex + 1) % $banners->count();
			$nextBanner = $banners[$nextIndex];
			@endphp
			<img src="/storage/banners/{{$nextBanner->image}}" alt="" id="nextBanner">
		</div>
	</div>
	<section class="boxes-container">
		<div class="boxes">
			<x-category-box image="/img/drones.webp" title="Drones" path="/products/10" />
			<x-category-box image="/img/ps.webp" title="Playstation" path="/products/1" />
			<x-category-box image="/img/watches-banner.webp" title="Watches" path="/watches" />
			<x-category-box image="/img/Elite Gear.webp" title="Gaming Setup" path="/products/5" />
		</div>
	</section>
	<section class="new">
		<div class="shadow"></div>
		<div class="section-title">
			<h1>Newest Release</h1>
		</div>

		<div class="products">
			@foreach ($newproducts as $product)
			<x-new-product-card image="{{$product->image}}" title="{{$product->name}}" price="{{$product->price}}"
				salePrice="{{$product->sale}}" category="{{$product->category->slogan}}" id="{{$product->id}}"
				isAvailable="{{$product->is_available}}" forceColoredCart="{{true}}" />
			@endforeach
		</div>
	</section>
	<section class="featured">
		<div class="shadow"></div>

		<div class="section-title">
			<h1>Featured Products</h1>
		</div>

		<div class="products">
			@foreach ($featuredProducts as $product)
			<x-new-product-card image="{{$product->image}}" title="{{$product->name}}" price="{{$product->price}}"
				salePrice="{{$product->sale}}" category="{{$product->category->slogan}}" id="{{$product->id}}"
				isAvailable="{{$product->is_available}}" forceColoredCart="{{true}}" />
			@endforeach
		</div>
	</section>
	<section class="ps5-controllers">
		<div class="header">
			<h2>PS5 Controllers</h2>
			<button class="view-all" onclick="window.location.href='/products/category/5'">View All</button>
		</div>
		<div class="controller-carousel">
			@foreach($controllers as $controller)
			<div class="controller-card">
				<a href="/product/{{$controller->id}}" style="text-decoration: none">
					<div class="controller-image-wrapper">
						<img src="/storage/products/{{$controller->image}}"
							alt="{{ html_entity_decode($controller->name) }}" loading="lazy" class="controller-img" />
						@if($controller->sale)
						<div class="sale-badge">SALE</div>
						@endif
					</div>
					<div class="controller-info-container">
						<h3 style="color:black">{{ html_entity_decode($controller->name) }}</h3>
					</div>

					@if($controller->sale)
					<div class="product-price">
						<span class="old-price">${{ number_format($controller->price, 2) }}</span>
						<span class="sale-price">${{ number_format($controller->sale, 2) }}</span>
					</div>
					@else
					<p class="price">${{ number_format($controller->price, 2) }}</p>
					@endif

					<button onclick="addToCart({{ $controller->id }})" style="font-family: 'Poppins', sans-serif;">
						Add to cart
						<img src="/img/colored-cart.svg" class="cart-icon" />
					</button>
				</a>
			</div>

			@endforeach
		</div>
	</section>
	<section class="watches-section">
		<div class="header">
			<h2>Watches</h2>
			<button class="view-all" onclick="window.location.href='/watches'">View All</button>
		</div>

		<div class="watch-carousel">
			@foreach($watches as $watch)
			<div class="watch-card">
				<a href="/watch/{{ $watch->id }}" style="text-decoration: none">
					<div class="watch-image-wrapper">
						<img src="/storage/watches/{{ $watch->image }}" alt="{{ html_entity_decode($watch->name) }}"
							loading="lazy" class="watch-img" />
						@if($watch->sale)
						<div class="sale-badge">SALE</div>
						@endif
					</div>

					<div class="watch-info-container">
						<h3 style="color:black">{{ html_entity_decode($watch->name) }}</h3>
					</div>

					@if($watch->sale)
					<div class="product-price">
						<span class="old-price">${{ number_format($watch->price, 2) }}</span>
						<span class="sale-price">${{ number_format($watch->sale, 2) }}</span>
					</div>
					@else
					<p class="price">${{ number_format($watch->price, 2) }}</p>
					@endif
				</a>
				<button onclick="addToCart({{ $watch->id }}, 'watch')" style="font-family: 'Poppins', sans-serif;">
					Add to cart
					<img src="/img/colored-cart.svg" class="cart-icon" />
				</button>

			</div>
			@endforeach
		</div>
	</section>



	<section class="info">
		<div class="general-info">
			<ul>
				<li>🛒 Hadath Beirut, Hayek Gaming Ground</li>
				<li>🚚 Delivery all over Lebanon</li>
				<li id="status">
					⏱️ <span class="open"></span> <span class="time" style="color:red"></span>
				</li>
			</ul>
		</div>
		<div class="map">
			<iframe
				src="https://www.google.com/maps/embed?pb=!1m23!1m12!1m3!1d106045.32744561206!2d35.447177911202736!3d33.84026439283012!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m8!3e6!4m0!4m5!1s0x151f190dacbe9df3%3A0x4a0513f5fbc00c9f!2shttps%3A%2F%2Fmaps.app.goo.gl%2F2wUnruz8XEdamk8DA%2C%20Hadath%200000!3m2!1d33.840291799999996!2d35.5295791!5e0!3m2!1sen!2slb!4v1746635875629!5m2!1sen!2slb"
				height="380" style="border:0;" allowfullscreen="" loading="lazy"
				referrerpolicy="no-referrer-when-downgrade"></iframe>
		</div>
	</section>


	<x-footer :categories="$categories" movingSentence="{{$movingSentence}}" />
	<div id="toast" class="toast"></div>
	<script src="/js/home.js?v=1"></script>
	<script src="/js/navbar.js"></script>
	<script src="/js/order.js"></script>
	<script>
		const now = new Date();
  const currentHour = now.getHours();

  const statusEl = document.getElementById('status');
  const openEl = statusEl.querySelector('.open');
  const timeEl = statusEl.querySelector('.time');

  const isBefore7PM = currentHour < 19;

  if (isBefore7PM) {
    openEl.textContent = 'Currently Open';
    openEl.classList.remove('closed'); // remove closed style
    timeEl.textContent = 'Close at 7:00 PM';
  } else {
    openEl.textContent = 'Currently Closed';
    openEl.classList.add('closed'); // apply red style
    timeEl.textContent = 'Open at 10:00 AM';
  }
	</script>
</body>

</html>