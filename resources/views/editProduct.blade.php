<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="icon" sizes="32x32" href="/img/white-logo.svg">
  <link rel="stylesheet" href="/css/fonts.css" />

  <link rel="stylesheet" href="/css/admin.css">
  <link rel="stylesheet" href="/css/addProduct.css">
  <title>Edit Product</title>
  <style>
    .img-preview {
      display: block;
    }
  </style>
</head>

<body>
  <x-navigator />
  <section class="addProduct-page">
    <h1 class="page-title">Edit {{$product->name}}</h1>

    <form method="POST" enctype="multipart/form-data" action="/admin/editProduct/{{$product->id}}">
      @csrf

      <div class="form-row">

        <div class="form-group">
          <label for="category">Category</label>
          <select id="category" required onchange="updateSubcategories()" name="category_id">
            <option value="" selected disabled>Select</option>
            @foreach ($categories as $category )
            <option value="{{$category->id}}" {{ old('category_id', $product->category_id) == $category->id ? 'selected'
              : '' }}>{{$category->name}}</option>
            @endforeach
          </select>

          @error('category_id')
          <p style="color:red">{{$message}}</p>
          @enderror
        </div>

        <div class="form-group">
          <label for="subcategory">Subcategory</label>
          <select id="subcategory" onchange="toggleGameType()" name="sub_category_id">
            <option value="">Select</option>
          </select>
          @error('sub_category_id')
          <p style="color:red">{{$message}}</p>
          @enderror
        </div>
        <div class="form-group" id="gameTypeGroup" style="display: none;">
          <label>Game Types</label>
          <div class="checkbox-group">
            @foreach ($gameTypes as $gameType)
            <label>
              <input type="checkbox" name="gameTypes[]" value="{{ $gameType->name }}" {{in_array($gameType->id,
              $product_gameTypes) ? 'checked' : '' }}>
              {{ $gameType->name }}
            </label>
            @endforeach
            @error('gameTypes[]')
            <p style="color:red">{{$message}}</p>
            @enderror
          </div>
        </div>


        <div class="form-row">
          <div class="form-group">
            <label for="name">Name (20 characters max)</label>
            <input type="text" id="smallName" required name="name" value="{{old('name',$product->name)}}">
            @error('name')
            <p style="color:red">{{$message}}</p>
            @enderror
          </div>


          <div class="form-row">
            <div class="form-group">
              <label for="price">Price ($)</label>
              <input type="decimal" id="price" required name="price" value="{{old('price',$product->price)}}">
              @error('price')
              <p style="color:red">{{$message}}</p>
              @enderror
            </div>
            <div class="form-group">
              <label for="salePrice">Sale Price ($) <small style="color: gray;">(optional)</small></label>
              <input type="decimal" id="salePrice" name="sale" value="{{ old('sale',$product->sale) }}">
              @error('sale')
              <p style="color:red">{{ $message }}</p>
              @enderror
            </div>
            <div class="form-group">
              <label for="cost">Cost ($)</label>
              <input type="decimal" id="cost" name="cost" value="{{ old('cost',$product->cost) }}">
              @error('cost')
              <p style="color:red">{{ $message }}</p>
              @enderror
            </div>

            <div class="form-group">
              <label for="isAvailable">Is Available</label>
              <select id="isAvailable" required name="is_available">
                <option value="yes" {{ old('is_available', $product->is_available) == 1 ? 'selected' : '' }}>Yes
                </option>
                <option value="no" {{ old('is_available', $product->is_available) == 0 ? 'selected' : '' }}>No</option>
              </select>
              @error('is_available')
              <p style="color:red">{{ $message }}</p>
              @enderror
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="isFeatured">Is Featured</label>
              <select id="isFeatured" required name="featured">
                <option value="no" {{ old('featured', $product->featured) == 0 ? 'selected' : '' }}>No</option>
                <option value="yes" {{ old('featured', $product->featured) == 1 ? 'selected' : '' }}>Yes</option>
              </select>
              @error('featured')
              <p style="color:red">{{$message}}</p>
              @enderror
            </div>
            <div class="form-group">
              <label for="isNew">Show in Newest?</label>
              <select id="isNew" required name="isNew">
                <option value="no" {{ old('isNew', $product->isNew) == 0 ? 'selected' : '' }}>No</option>
                <option value="yes" {{ old('isNew', $product->isNew) == 1 ? 'selected' : '' }}>Yes</option>
              </select>
              @error('isNew')
              <p style="color:red">{{$message}}</p>
              @enderror
            </div>
          </div>


          <div class="form-group">
            <label for="smallDesc">Small Description</label>
            <textarea id="smallDesc" rows="3" 
              name="description">{{old('description' ,$product->description)}}</textarea>
            @error('description')
            <p style="color:red">{{$message}}</p>
            @enderror
          </div>

          <div class="form-group">
            <label for="features">List of Features (one per line, optional)</label>
            <textarea id="features" rows="5" name="features">{{old('features',$features)}}</textarea>
            @error('features')
            <p style="color:red">{{$message}}</p>
            @enderror
          </div>

          <div class="form-group">
            <label for="boxContents">Box Contents (one per line, optional)</label>
            <textarea id="boxContents" rows="4" name="box_contents">{{old('box_contents',$boxContents)}}</textarea>
            @error('box_contents')
            <p style="color:red">{{$message}}</p>
            @enderror
          </div>

          <div class="form-group image-upload">
            <label for="mainImage">Main Image</label>
            <input type="file" id="mainImage" accept="image/*" name="image">
            @error('image')
            <p style="color:red">{{$message}}</p>
            @enderror
            <img id="preview-mainImage" class="img-preview" src="/storage/products/{{$product->image}}"
              loading="lazy" />
          </div>

          <div class="form-row">
            <div class="form-group image-upload">
              <label for="img1">Image 1</label>
              <input type="file" id="img1" accept="image/*" name="image1">
              @error('image1')
              <p style="color:red">{{$message}}</p>
              @enderror
              <img id="preview-img1" class="img-preview" src="/storage/products/{{$product->image1}}" loading="lazy" />
            </div>
            <div class="form-group image-upload">
              <label for="img2">Image 2</label>
              <input type="file" id="img2" accept="image/*" name="image2">
              @error('image2')
              <p style="color:red">{{$message}}</p>
              @enderror
              <img id="preview-img2" class="img-preview" src="/storage/products/{{$product->image2}}" loading="lazy" />
            </div>
            <div class="form-group image-upload">
              <label for="img3">Image 3</label>
              <input type="file" id="img3" accept="image/*" name="image3">
              @error('image3')
              <p style="color:red">{{$message}}</p>
              @enderror
              <img id="preview-img3" class="img-preview" src="/storage/products/{{$product->image3}}" loading="lazy" />
            </div>
            <div class="form-group image-upload">
              <label for="img4">Image 4</label>
              <input type="file" id="img4" accept="image/*" name="image4">
              @error('image4')
              <p style="color:red">{{$message}}</p>
              @enderror
              <img id="preview-img4" class="img-preview" style="max-width:400px;"
                src="/storage/products/{{$product->image4}}" loading="lazy" />
            </div>
            <div class="form-group image-upload">
              <label for="img5">Image 5</label>
              <input type="file" id="img5" accept="image/*" name="image5">
              @error('image5')
              <p style="color:red">{{$message}}</p>
              @enderror
              <img id="preview-img5" class="img-preview" style="max-width:400px;"
                src="/storage/products/{{$product->image5}}" loading="lazy" />
            </div>
            <div class="form-group image-upload">
              <label for="img6">Image 6</label>
              <input type="file" id="img6" accept="image/*" name="image6">
              @error('image4')
              <p style="color:red">{{$message}}</p>
              @enderror
              <img id="preview-img6" class="img-preview" style="max-width:400px;"
                src="/storage/products/{{$product->image6}}" loading="lazy" />
            </div>
          </div>
          <input type="submit" value="Edit Product" class="submit-btn" />
        </div>
    </form>
  </section>
  <script>
    const subcategories = @json($categories->mapWithKeys(function($category) {
        return [$category->id => $category->subcategories->pluck('name', 'id')];
    }));
    console.log(subcategories);

  function updateSubcategories() {
    const category = document.getElementById('category').value;
    const subSelect = document.getElementById('subcategory');
    subSelect.innerHTML = '<option value="">Select</option>';

    if (subcategories[category]) {
      const subcats = subcategories[category];
      Object.entries(subcats).forEach(sub => {
        const opt = document.createElement('option');
        opt.value = sub[0];
        opt.textContent = sub[1];
        subSelect.appendChild(opt);
      });
    }

    toggleGameType();
  }

  let initiallyLoaded = true;

function toggleGameType() {
  const sub = document.getElementById('subcategory');
  const selectedText = sub.options[sub.selectedIndex]?.text?.toLowerCase();
  const gameType = document.getElementById('gameTypeGroup');
  
  if (selectedText === 'games') {
    gameType.style.display = 'block';
  } else {
    gameType.style.display = 'none';
    if (!initiallyLoaded) {
      // Only uncheck checkboxes if user changed selection
      document.querySelectorAll('input[name="gameTypes[]"]').forEach(cb => cb.checked = false);
    }
  }
}
  const imageInputs = ['mainImage', 'img1', 'img2', 'img3', 'img4','img5','img6'];

  imageInputs.forEach(id => {
    const input = document.getElementById(id);
    const preview = document.getElementById('preview-' + id);

    input.addEventListener('change', function () {
      const file = input.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
          preview.src = e.target.result;
          preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
      } else {
        preview.src = '';
        preview.style.display = 'none';
      }
    });
  });
  const initialSubcategoryId = "{{ old('sub_category_id', $product->sub_category_id) }}";

  document.addEventListener("DOMContentLoaded", function () {
    const categorySelect = document.getElementById('category');
    const subSelect = document.getElementById('subcategory');

    // Prepopulate subcategories based on selected category
    updateSubcategories();

    // Preselect the correct subcategory
    if (initialSubcategoryId) {
      subSelect.value = initialSubcategoryId;
    }

    // Trigger game type display logic based on the selected subcategory
    toggleGameType();

    // Set flag to false so we don't reset checkboxes on load
    initiallyLoaded = false;
  });
  </script>

</body>

</html>