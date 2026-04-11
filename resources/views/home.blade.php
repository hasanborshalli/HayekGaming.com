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
	<link rel="stylesheet" href="/css/footer.css">
	<link rel="stylesheet" href="/css/sidebar.css">
	<link rel="stylesheet" href="/css/toast.css">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="stylesheet" href="/css/fonts.css" />

	<title>Hayek Gaming Ground</title>
</head>

<body>
	<x-navbar :categories="$categories" cartQuantity="{{$cartQuantity}}" />
	
	<section class="boxes-container">
		<div class="boxes">
			<x-category-box image="/img/drones.webp" title="Drones" path="/products/10" />
			<x-category-box image="/img/ps.webp" title="Playstation" path="/products/1" />
			<x-category-box image="/img/watches-banner.webp" title="Watches" path="/watches" />
			<x-category-box image="/img/Elite Gear.webp" title="Gaming Setup" path="/products/5" />
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