<nav class="navbar">
    <div class="navbar-left">
        <div class="burger"><img src="/img/burger.svg" loading="lazy" /></div>
        <a href="/"><img src="/img/white-logo.svg" alt="HGG Logo" class="logo" loading="lazy" /></a>
    </div>

    <div class="navbar-center">
        <div class="logo-mobile">
            <a href="/"> <img src="/img/colored-logo.webp" alt="HGG Logo" loading="lazy" /></a>
        </div>
        <form action="/search/products" method="GET">
            <input type="text" placeholder="Search the store" name="search" autocomplete="off" id="search" />
            <div id="search-results"></div>
        </form>
    </div>

    <div class="navbar-right web">
        <div class="cart-wrapper" onclick="window.location.href='/cart'">
            <img src="/img/cart.svg" alt="Cart Icon" class="icon" loading="lazy" />
            <div class="cart-quantity">{{$cartQuantity}}</div>
        </div>
    </div>
    <div class="navbar-right mobile">
        <div class="cart-wrapper" onclick="window.location.href='/cart'">
            <img src="/img/colored-cart.svg" alt="Cart Icon" class="icon" loading="lazy" />
            <div class="cart-quantity">{{$cartQuantity}}</div>
        </div>
    </div>
</nav>
<div class="categories-container">
    <ul class="categories">
        <a href="/">
            <li>Home</li>
        </a>
        @foreach ($categories as $category)
        @if($category->subcategories->count()>0)
        <li>

            <div class="dropdown">
                <span class="dropdown-toggle">
                    <a href="/products/{{$category->id}}">{{$category->name}}</a>
                    <span class="arrow">˅</span>
                </span>
                <ul class="dropdown-menu">
                    @foreach ($category->subcategories as $subcategory)
                    <a href="/products/category/{{$subcategory->id}}">
                        <li>{{$subcategory->name}}</li>
                    </a>
                    @endforeach
                </ul>
            </div>
        </li>

        @endif
        @endforeach

    </ul>
</div>
<div class="search-container">
    <form action="/search/products" method="GET">
        @csrf
        <input type="text" name="search" placeholder="Search..." class="search-input" id="mobile-search"
            autocomplete="off" />
        <div id="mobile-search-results"></div>
        <button type="submit" class="search-btn">
            <img src="/img/search.svg" alt="Search Icon" class="search-icon" loading="lazy" />
        </button>
    </form>
</div>

<x-side-bar :categories="$categories" />