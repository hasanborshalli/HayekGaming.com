<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="icon" sizes="32x32" href="/img/white-logo.svg">
  <link rel="stylesheet" href="/css/fonts.css" />

  <link rel="stylesheet" href="/css/admin.css">
  <link rel="stylesheet" href="/css/banners.css">
  <link rel="stylesheet" href="/css/addBanner.css">
  <title>Edit Banner</title>
</head>

<body>
  <x-navigator />
  <section class="addBanner-page">
    <h1 class="page-title">Edit Banner</h1>

    <form id="bannerForm" method="POST" action="/admin/editBanner/{{$banner->id}}" enctype="multipart/form-data">
      @csrf
      <div class="form-group">
        <label for="desktopImage">Desktop Banner Image (1400×700)</label>
        <input type="file" id="desktopImage" accept="image/*" name="image">
        <img id="desktopPreview" class="preview" src="/storage/banners/{{ $banner->image }}" style="display: block;"
          loading="lazy" />
        @error('image')
        <p style="color:red">{{$message}}</p>
        @enderror

      </div>

      <div class="form-group">
        <label for="mobileImage">Mobile Banner Image (700×880)</label>
        <input type="file" id="mobileImage" accept="image/*" name="mobile_image">
        <img id="mobilePreview" class="preview" src="/storage/banners/{{ $banner->mobile_image }}"
          style="display: block;" loading="lazy" />

        @error('mobile_image')
        <p style="color:red">{{$message}}</p>
        @enderror
      </div>
      <div class="form-group">
        <label for="smallImage">Small Banner Image (700×880)</label>
        <input type="file" id="smallImage" accept="image/*" name="small_image">
        <img id="smallPreview" class="preview" src="/storage/banners/{{ $banner->small_image }}" style="display: block;"
          loading="lazy" />

        @error('small_image')
        <p style="color:red">{{$message}}</p>
        @enderror
      </div>
      <div class="form-group">
        <label for="linkedProduct">Linked Product</label>
        <select id="linkedProduct" name="product_id" required>
          <option value="">Select a product</option>
          @foreach ($products as $product)
          <option value="{{ $product->id }}" {{ $product->id == $banner->product_id ? 'selected' : '' }}>
            {{ $product->name }}
          </option>
          @endforeach
        </select>
      </div>

      <button type="submit">Edit Banner</button>
    </form>
  </section>
  <script src="/js/addBanner.js">
  </script>
</body>

</html>