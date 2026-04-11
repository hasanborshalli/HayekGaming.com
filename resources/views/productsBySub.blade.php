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
  <link rel="stylesheet" href="/css/fonts.css" />

    <link rel="icon" sizes="32x32" href="/img/white-logo.svg">
    <link rel="stylesheet" href="/css/navbar.css" />
    <link rel="stylesheet" href="/css/footer.css">
    <link rel="stylesheet" href="/css/sidebar.css">
    <link rel="stylesheet" href="/css/productsList.css?v=1">
    <link rel="stylesheet" href="/css/productsPage.css">
    <link rel="stylesheet" href="/css/relatedProducts.css">
    <link rel="stylesheet" href="/css/pagination.css">
    <link rel="stylesheet" href="/css/toast.css">


    <title>Products Page</title>
</head>

<body>
    <x-navbar :categories="$categories" cartQuantity="{{$cartQuantity}}" />
    <section class="productsPage">
        <div class="path">
            @if ($isGameType)
            <h3><a href="/">Home </a>> <a
                    href="/products/{{$subCategory->category->id}}">{{$subCategory->category->name}}</a> > <a
                    href="/products/category/{{$subCategory->id}}">{{$subCategory->name}}</a> > <span
                    style="color:rgb(0,0,0);">
                    @if($gameType == "All Games")
                    All Games
                    @else
                    {{$gameType->name}}

                    @endif
                </span></h3>
            @else
            <h3><a href="/">Home </a>> <a
                    href="/products/{{$subCategory->category->id}}">{{$subCategory->category->name}}</a> > <span
                    style="color:rgb(0,0,0);">{{$subCategory->name}} </span></h3>
            @endif
        </div>
        @if ($subCategory->name=='Games')
        <!-- Sticky Filter Button -->
        <button class="filter-toggle-btn" onclick="toggleFilterForm()">Filter</button>

        <!-- Hidden Filter Form -->
        <div id="filterFormContainer" class="filter-form-popup">
            <button type="button" class="close-filter-btn" onclick="toggleFilterForm()">×</button>
            <form method="GET" action="/filterProducts" class="gameType-filter-form">
                <h4>Filter by Game Types:</h4>
                <input type="hidden" name="subCategoryId" value="{{ $subCategory->id }}">
                <div class="gameTypes">
                    @foreach ($gameTypes as $gameType)
                    <label>
                        <input type="checkbox" name="gameTypes[]" value="{{ $gameType->id }}">
                        {{ $gameType->name }}
                    </label><br>
                    @endforeach
                </div>
                <button type="submit">Apply Filter</button>
            </form>
        </div>

        @if($isGameType)
        <section class="productsList">
            @foreach ($products as $product)
            <x-new-product-card image="{{$product->image}}" title="{{$product->name}}" price="{{$product->price}}"
                salePrice="{{$product->sale}}" category="{{$product->category->slogan}}" id="{{$product->id}}"
                isAvailable="{{$product->is_available}}" />

            @endforeach
        </section>
        {{ $products->links() }}
        @endif

        @else
        <h3>{{$subCategory->name}}</h3>
        <section class="productsList">
            @foreach ($products as $product)
            <x-new-product-card image="{{$product->image}}" title="{{$product->name}}" price="{{$product->price}}"
                salePrice="{{$product->sale}}" category="{{$product->category->slogan}}" id="{{$product->id}}"
                isAvailable="{{$product->is_available}}" />

            @endforeach
        </section>
        {{ $products->links() }}
        @endif


    </section>
    <div id="toast" class="toast"></div>
    <x-footer :categories="$categories" movingSentence="{{$movingSentence}}" />
    <script src="/js/navbar.js"></script>
    <script src="/js/order.js"></script>
    <script>
        function toggleFilterForm() {
        const popup = document.getElementById('filterFormContainer');
        popup.classList.toggle('show');
    }
    </script>
</body>

</html>