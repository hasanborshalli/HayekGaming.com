<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="icon" sizes="32x32" href="/img/white-logo.svg">
  <link rel="stylesheet" href="/css/fonts.css" />

  <link rel="stylesheet" href="/css/admin.css">
  <link rel="stylesheet" href="/css/manageComing.css">
  <title>Coming Soon Image</title>
</head>

<body>
  @if (session('message'))
  <div id="toast" class="toast">{{ session('message') }}</div>
  <script>
    // Show toast for 3 seconds then fade out
        window.addEventListener('DOMContentLoaded', () => {
            const toast = document.getElementById('toast');
            toast.classList.add('show');
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        });
  </script>
  @endif
  <x-navigator />

  <section class="manageComing-page">
    <h1 class="page-title">Coming Soon Manager</h1>

    <!-- Top Buttons -->
    <div class="top-buttons">
      <button type="button" class="btn green" onclick="addProduct()">Add Coming
        Product</button>

      <button class="btn" onclick="document.getElementById('changeComingWrapper').scrollIntoView()">Change Coming Soon
        Image</button>
    </div>
    <div class="image-container add-form" id="add-form">
      <h3>Add Coming Product (Game Image)</h3>
      @error('coming_product')
      <p style="color:red">{{ $message }}</p>
      @enderror
      <form action="/admin/comingSoon/addProduct" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" accept="image/*" name="image" id="newComingProduct" required>
        <br>
        <img id="previewComingProduct" style="max-width: 100%; margin-top: 1rem; display: none; border-radius: 8px;" />
        <br>
        <button type="submit" class="btn green">Add Coming Product</button>
      </form>
    </div>
    <!-- Coming Product Cards -->
    <div class="coming-products-grid">
      @foreach ($comingProducts as $product)
      <div class="coming-card" data-id="{{ $product->id }}">
        <img src="/storage/comingSoon/{{ $product->image }}" alt="Coming Product">

        <div class="card-actions">
          <button class="btn" onclick="toggleEditForm({{ $product->id }})">Edit</button>
          <button class="btn red" onclick="deleteProduct({{ $product->id }}, this, 'product')">Delete</button>
        </div>

        <!-- Hidden edit form -->
        <form action="/admin/comingSoon/editProduct/{{ $product->id }}" method="POST" enctype="multipart/form-data"
          id="edit-form-{{ $product->id }}" class="edit-form" style="display: none; margin-top: 10px;">
          @csrf
          <input type="file" name="image" accept="image/*" required>
          <button type="submit" class="btn">Save</button>
        </form>
      </div>
      @endforeach

    </div>

    <!-- Change Coming Soon Image Form -->
    <div class="image-container" id="changeComingWrapper">
      <h3>Coming Soon Image (700x380)</h3>
      <form action="/admin/comingSoon/edit" method="POST" enctype="multipart/form-data">
        @csrf
        <img src="/storage/comingSoon/{{$image->image}}" alt="Coming Soon Image" id="comingSoonImg" loading="lazy">
        <input type="file" accept="image/*" name="image" id="imageInput" style="display: none;" required>
        <button type="button" class="btn" onclick="document.getElementById('imageInput').click()">Change Image</button>
        <br>
        <button type="submit" class="btn">Save Image</button>
      </form>
    </div>
  </section>
  <div id="delete-toast" class="delete-toast"></div>

  <div id="confirmOverlay" class="overlay" style="display: none;">
    <div class="confirm-box">
      <p id="confirmation-message"></p>
      <div class="buttons">
        <button class="btn red" id="confirmYes">Yes</button>
        <button class="btn" id="confirmNo">No</button>
      </div>
    </div>
  </div>

  <script src="/js/manageComing.js">

  </script>


</body>

</html>