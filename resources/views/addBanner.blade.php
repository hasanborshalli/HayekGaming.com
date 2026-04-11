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
  <title>Add Banner</title>
</head>

<body>
  <x-navigator />
  <section class="addBanner-page">
    <h1 class="page-title">Add New Banner</h1>

    <form id="bannerForm" method="POST" action="/admin/addBanner" enctype="multipart/form-data">
      @csrf
      <div class="form-group">
        <label for="desktopImage">Desktop Banner Image (1400×700)</label>
        <input type="file" id="desktopImage" accept="image/*" required name="image">
        @error('image')
        <p style="color:red">{{$message}}</p>
        @enderror
        <img id="desktopPreview" class="preview" style="display: none;" loading="lazy" />
      </div>

      <div class="form-group">
        <label for="mobileImage">Mobile Banner Image (700×880)</label>
        <input type="file" id="mobileImage" accept="image/*" required name="mobile_image">
        @error('mobile_image')
        <p style="color:red">{{$message}}</p>
        @enderror
        <img id="mobilePreview" class="preview" style="display: none;" loading="lazy" />
      </div>
      <div class="form-group">
        <label for="smallImage">Small Banner Image (700×880)</label>
        <input type="file" id="smallImage" accept="image/*" required name="small_image">
        @error('small_image')
        <p style="color:red">{{$message}}</p>
        @enderror
        <img id="smallPreview" class="preview" style="display: none;" loading="lazy" />
      </div>
      <div class="form-group">
        <label for="linkedProduct">Linked Product</label>
        <select id="linkedProduct" required name="product_id">
          <option value="">Select a product</option>
          @foreach ($products as $product)
          <option value="{{$product->id}}">{{html_entity_decode($product->name)}}</option>
          @endforeach
        </select>
      </div>

      <button type="submit">Add Banner</button>
    </form>
  </section>
  <script src="/js/addBanner.js">

  </script>
</body>

</html>