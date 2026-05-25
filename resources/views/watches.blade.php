<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  {{-- SEO --}}
  <meta name="robots" content="index, follow" />
  <meta name="keywords"
    content="Hayek Gaming, Hayek Gaming Ground, Watches Lebanon, Smart Watches Lebanon, Digital Watches, Gold Watches, Silver Watches, Luxury Watches, Electronic Gadgets, Accessories, Fashion Tech, Gifts Lebanon" />
  <meta name="author" content="Hayek Gaming Ground" />
  <meta name="copyright" content="Copyright © 2024 HayekGaming" />
  <meta name="theme-color" content="#2a2670" />
  <meta name="description"
    content="Explore Hayek Gaming's exclusive collection of watches in Lebanon — including digital, analog, smart, and luxury gold & silver watches. Shop with fast delivery and trusted quality." />

  <meta property="og:title" content="Watches | Hayek Gaming Ground" />
  <meta property="og:description"
    content="Discover premium watches at Hayek Gaming — digital, smart, and analog styles for gamers and professionals alike." />
  <meta property="og:image" content="https://www.hayekgaming.com/img/white-logo.svg" />
  <meta property="og:url" content="https://www.hayekgaming.com/watches" />
  <meta property="og:type" content="website" />
  {{-- End of SEO --}}

  {{-- Fonts --}}
  <link rel="stylesheet" href="/css/fonts.css" />


  <link rel="icon" sizes="32x32" href="/img/white-logo.svg">

  {{-- Shared Styles --}}
  <link rel="stylesheet" href="/css/navbar.css" />
  <link rel="stylesheet" href="/css/footer.css">
  <link rel="stylesheet" href="/css/productsList.css">
  <link rel="stylesheet" href="/css/productsPage.css">
  <link rel="stylesheet" href="/css/sidebar.css">
  <link rel="stylesheet" href="/css/toast.css">

  {{-- Watches Page Style --}}
  <link rel="stylesheet" href="/css/watches.css?v=1.1">

  <title>Watches | Hayek Gaming Ground</title>
</head>

<body class="watches-page">
  {{-- Navbar --}}
  <x-navbar :categories="$categories" cartQuantity="{{ $cartQuantity }}" />

  {{-- Main Content --}}
  <section class="productsPage">
    <div class="path">
      <h3>Home > <span style="color:black;">{{$path}}</span></h3>
    </div>


    <section class="productsList">
      @foreach ($watches as $watch)
      <x-watch-card title="{{ $watch->name }}" price="{{ $watch->price }}" salePrice="{{ $watch->sale }}"
        type="{{ $watch->type->name }}" id="{{ $watch->id }}" isAvailable="{{ $watch->is_available }}"
        image="{{ $watch->image }}" image1="{{ $watch->image1 }}" image2="{{ $watch->image2 }}"
        image3="{{ $watch->image3 }}" image4="{{ $watch->image4 }}" image5="{{ $watch->image5 }}"
        image6="{{ $watch->image6 }}" color="{{ $watch->color }}" color1="{{ $watch->color1 }}"
        color2="{{ $watch->color2 }}" color3="{{ $watch->color3 }}" color4="{{ $watch->color4 }}"
        color5="{{ $watch->color5 }}" color6="{{ $watch->color6 }}" />
      @endforeach
    </section>
    {{ $watches->links() }}
  </section>

  {{-- Toast notification --}}
  <div id="toast" class="toast"></div>

  {{-- Footer --}}
  <x-footer :categories="$categories" movingSentence="{{ $movingSentence }}" />

  {{-- Scripts --}}
  <script src="/js/navbar.js"></script>
  <script src="/js/order.js"></script>
  <script>
    (function initWatchCards(){
  // helper: safely find closest anchor to temporarily disable clicks while swiping
  function preventClickWhileSwiping(anchor){
    const onClick = (e)=> {
      if (anchor.dataset.blockClick === '1') {
        e.preventDefault();
        e.stopPropagation();
      }
      // reset after the event cycle
      requestAnimationFrame(()=>{ anchor.dataset.blockClick = '0'; });
    };
    // only attach once
    if (!anchor.__clickBound) {
      anchor.addEventListener('click', onClick, true);
      anchor.__clickBound = true;
    }
  }

  // go to slide index
  function goTo(idx, track, total, dots){
    const clamped = Math.max(0, Math.min(idx, total-1));
    track.style.transform = `translateX(-${clamped * 100}%)`;
    if (dots) {
      dots.forEach(d => d.classList.remove('active-dot'));
      if (dots[clamped]) dots[clamped].classList.add('active-dot');
    }
    track.__current = clamped;
  }

  // attach per-card logic
  document.querySelectorAll('.watch-slider').forEach((slider)=>{
    const cardId = slider.getAttribute('data-card-id');
    const track  = slider.querySelector('.watch-track');
    const slides = track ? track.children.length : 0;
    if (!track || slides === 0) return;

    // dots bound to this card
    const dotsWrap = document.querySelector(`.color-dots-container[data-card-id="${cardId}"]`);
    const dots = dotsWrap ? Array.from(dotsWrap.querySelectorAll('.color-dot')) : null;

    // anchor to block during swipe
    const anchor = slider.closest('.image-wrapper');
    if (anchor) preventClickWhileSwiping(anchor);

    // set initial
    track.__current = 0;
    goTo(0, track, slides, dots);

    // dot click -> jump
    if (dots) {
      dots.forEach(dot => {
        dot.addEventListener('click', (e)=>{
          e.preventDefault();
          e.stopPropagation();
          const targetIdx = parseInt(dot.getAttribute('data-index'),10) || 0;
          goTo(targetIdx, track, slides, dots);
        });
      });
    }

    // swipe state
    let startX = 0, currentX = 0, moved = 0, isTouching = false, hasSwiped = false;

    function onStart(clientX){
      isTouching = true;
      hasSwiped = false;
      moved = 0;
      startX = clientX;
      currentX = clientX;
      // temporarily remove transition to follow finger
      track.style.transition = 'none';
    }
    function onMove(clientX){
      if (!isTouching) return;
      currentX = clientX;
      const dx = currentX - startX;
      moved = dx;

      // block anchor click if finger moved enough
      if (Math.abs(moved) > 5 && anchor) anchor.dataset.blockClick = '1';

      const width = slider.clientWidth;
      const progress = dx / width;
      const base = track.__current * -100;
      track.style.transform = `translateX(calc(${base}% + ${dx}px))`;
    }
    function onEnd(){
      if (!isTouching) return;
      isTouching = false;

      // restore transition
      requestAnimationFrame(()=>{ track.style.transition = 'transform 280ms ease'; });

      const width = slider.clientWidth;
      const threshold = Math.max(40, width * 0.15); // swipe threshold
      let next = track.__current;

      if (moved <= -threshold) { next = track.__current + 1; hasSwiped = true; }
      if (moved >=  threshold) { next = track.__current - 1; hasSwiped = true; }

      goTo(next, track, slides, dots);

      // small delay before re-enabling clicks to avoid ghost-clicks
      if (anchor) setTimeout(()=>{ anchor.dataset.blockClick = '0'; }, 60);
    }

    // touch
    slider.addEventListener('touchstart', (e)=> onStart(e.touches[0].clientX), {passive:true});
    slider.addEventListener('touchmove',  (e)=> onMove(e.touches[0].clientX),  {passive:true});
    slider.addEventListener('touchend',   onEnd, {passive:true});

    // mouse (desktop)
    slider.addEventListener('mousedown', (e)=> { e.preventDefault(); onStart(e.clientX); });
    window.addEventListener('mousemove', (e)=> onMove(e.clientX));
    window.addEventListener('mouseup', onEnd);
  });
})();
  </script>


</body>

</html>