<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" sizes="32x32" href="/img/white-logo.svg">
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
    <link rel="stylesheet" href="/css/navbar.css" />
    <link rel="stylesheet" href="/css/footer.css">
    <link rel="stylesheet" href="/css/sidebar.css">
    <link rel="stylesheet" href="/css/checkout.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="stylesheet" href="/css/fonts.css" />

    <title>Checkout</title>

</head>

<body>
    <x-navbar :categories="$categories" cartQuantity="{{$cartQuantity}}" />
    <section class="checkout-wrapper">
        <div class="checkout-container">
            <h2>Checkout Form</h2>
            <form action="/order" method="POST" class="checkout-form">
                @csrf
                <div class="form-group">
                    <label for="full_name">Full Name</label>
                    <input type="text" id="full_name" name="name" required placeholder="Enter your full name"
                        value="{{old('name')}}">
                    @error('name')
                    <p style="color:red">{{$message}}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="mobile">Mobile Number</label>
                    <div style="display: flex; align-items: center; gap: 5px;">
                        <span>🇱🇧 +961</span>
                        <input type="text" id="mobile" name="mobile" required
                            placeholder="Enter your mobile number without +961" value="{{ old('mobile') }}"
                            style="flex: 1;" maxlength="8">
                    </div>
                    @error('mobile')
                    <p style="color:red">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="second_mobile">Second Mobile Number</label>
                    <div style="display: flex; align-items: center; gap: 5px;">
                        <span>🇱🇧 +961</span>
                        <input type="text" id="second_mobile" name="second_mobile" placeholder="Optional"
                            value="{{ old('second_mobile') }}" style="flex: 1;" maxlength="8">
                    </div>
                    @error('second_mobile')
                    <p style="color:red">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" id="city" name="city" required placeholder="Enter your city"
                        value="{{old('city')}}">
                    @error('city')
                    <p style="color:red">{{$message}}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="street">Street</label>
                    <input type="text" id="street" name="street" required placeholder="Enter your street name"
                        value="{{old('street')}}">
                    @error('street')
                    <p style="color:red">{{$message}}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="building">Building</label>
                    <input type="text" id="building" name="building" placeholder="Enter your building (optional)"
                        value="{{old('building')}}">
                    @error('building')
                    <p style="color:red">{{$message}}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="floor">Floor</label>
                    <input type="text" id="floor" name="floor" placeholder="Enter your floor (optional)"
                        value="{{old('floor')}}">
                    @error('floor')
                    <p style="color:red">{{$message}}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="remarks">Remarks</label>
                    <textarea id="remarks" name="remarks"
                        placeholder="Enter any remarks (optional)">{{old('remarks')}}</textarea>
                    @error('remarks')
                    <p style="color:red">{{$message}}</p>
                    @enderror
                </div>

                <button type="submit" class="submit-btn">Send Order</button>
            </form>
        </div>
    </section>
    <x-footer :categories="$categories" movingSentence="{{$movingSentence}}" />
    <script src="/js/navbar.js"></script>
</body>