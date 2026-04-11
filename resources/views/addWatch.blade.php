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
    <title>Add Watch</title>
</head>

<body>
    <x-navigator />
    <section class="addProduct-page">
        <h1 class="page-title">Add New Watch</h1>

        <form method="POST" enctype="multipart/form-data" action="/admin/addWatch">
            @csrf

            <div class="form-row">
                <div class="form-row">
                    <div class="form-group">
                        <label for="smallName">Name</label>
                        <input type="text" id="smallName" required name="name" value="{{old('name')}}">
                        @error('name')
                        <p style="color:red">{{$message}}</p>
                        @enderror
                    </div>


                    <div class="form-row">
                        <div class="form-group">
                            <label for="price">Price ($)</label>
                            <input type="decimal" id="price" required name="price" value="{{old('price')}}">
                            @error('price')
                            <p style="color:red">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="salePrice">Sale Price ($) <small style="color: gray;">(optional)</small></label>
                            <input type="decimal" id="salePrice" name="sale" value="{{ old('sale') }}">
                            @error('sale')
                            <p style="color:red">{{ $message }}</p>
                            @enderror

                        </div>
                        <div class="form-group">
                            <label for="cost">Cost</label>
                            <input type="decimal" id="cost" name="cost" value="{{ old('cost') }}">
                            @error('cost')
                            <p style="color:red">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="isFeatured">Is Featured</label>
                            <select id="isFeatured" required name="featured">
                                <option value="no">No</option>
                                <option value="yes">Yes</option>
                            </select>
                            @error('featured')
                            <p style="color:red">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="watchType">Watch Type</label>
                            <select id="watchType" name="type_id" required>
                                <option value="">Select Type</option>
                                @foreach ($types as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                            @error('type_id')
                            <p style="color:red">{{$message}}</p>
                            @enderror
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="smallDesc">Small Description</label>
                        <textarea id="smallDesc" rows="3" name="description">{{old('description')}}</textarea>
                        @error('description')
                        <p style="color:red">{{$message}}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="features">List of Features (one per line, optional)</label>
                        <textarea id="features" rows="5" name="features">{{old('features')}}</textarea>
                        @error('features')
                        <p style="color:red">{{$message}}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="boxContents">Box Contents (one per line, optional)</label>
                        <textarea id="boxContents" rows="4" name="box_contents">{{old('box_contents')}}</textarea>
                        @error('box_contents')
                        <p style="color:red">{{$message}}</p>
                        @enderror
                    </div>

                    <div class="form-group image-upload">
                        <label for="mainImage">Main Image</label>
                        <input type="file" id="mainImage" accept="image/*" required name="image">
                        @error('image')
                        <p style="color:red">{{$message}}</p>
                        @enderror
                        <img id="preview-mainImage" class="img-preview" loading="lazy" />
                    </div>

                    <div class="form-row">
                        <div class="form-group image-upload">
                            <label for="img1">Image 1</label>
                            <input type="file" id="img1" accept="image/*" name="image1">
                            @error('image1')
                            <p style="color:red">{{$message}}</p>
                            @enderror
                            <img id="preview-img1" class="img-preview" loading="lazy" />
                        </div>
                        <div class="form-group image-upload">
                            <label for="img2">Image 2</label>
                            <input type="file" id="img2" accept="image/*" name="image2">
                            @error('image2')
                            <p style="color:red">{{$message}}</p>
                            @enderror
                            <img id="preview-img2" class="img-preview" loading="lazy" />
                        </div>
                        <div class="form-group image-upload">
                            <label for="img3">Image 3</label>
                            <input type="file" id="img3" accept="image/*" name="image3">
                            @error('image3')
                            <p style="color:red">{{$message}}</p>
                            @enderror
                            <img id="preview-img3" class="img-preview" loading="lazy" />
                        </div>
                        <div class="form-group image-upload">
                            <label for="img4">Image 4</label>
                            <input type="file" id="img4" accept="image/*" name="image4">
                            @error('image4')
                            <p style="color:red">{{$message}}</p>
                            @enderror
                            <img id="preview-img4" class="img-preview" style="max-width:400px;" loading="lazy" />
                        </div>
                        <div class="form-group image-upload">
                            <label for="img5">Image 5</label>
                            <input type="file" id="img5" accept="image/*" name="image5">
                            @error('image5')
                            <p style="color:red">{{$message}}</p>
                            @enderror
                            <img id="preview-img5" class="img-preview" style="max-width:400px;" loading="lazy" />
                        </div>
                        <div class="form-group image-upload">
                            <label for="img6">Image 6</label>
                            <input type="file" id="img6" accept="image/*" name="image6">
                            @error('image6')
                            <p style="color:red">{{$message}}</p>
                            @enderror
                            <img id="preview-img6" class="img-preview" style="max-width:400px;" loading="lazy" />
                        </div>
                    </div>
                    <!-- Dynamic Colors Container -->
                    <div class="form-group" id="colorsContainer">
                        <label>Colors for Each Image</label>
                        <div id="colorInputs"></div>
                    </div>

                    <input type="submit" value="Add Watch" class="submit-btn" />
                </div>
        </form>
    </section>
    <script>
        const imageInputs = ['mainImage', 'img1', 'img2', 'img3', 'img4', 'img5', 'img6'];
const colorsContainer = document.getElementById('colorInputs');

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
    updateColorInputs();
  });
});

function updateColorInputs() {
  // Save previously selected color values
  const existingColors = {};
  document.querySelectorAll('#colorInputs input[type="color"]').forEach((input, index) => {
    existingColors[index + 1] = input.value;
  });

  // Count how many images are currently selected
  const chosenCount = imageInputs.filter(id => document.getElementById(id).files.length > 0).length;

  // Clear the container before rebuilding
  colorsContainer.innerHTML = '';

  // Rebuild color inputs while restoring old values
  for (let i = 1; i <= chosenCount; i++) {
    const label = document.createElement('label');
    label.textContent = `Color for Image ${i}`;
    label.style.display = "block";
    label.style.marginTop = "8px";

    const input = document.createElement('input');
    input.type = 'color';
    if(i-1==0){
        input.name=`color`;
    }else{
    input.name = `color${i-1}`;

    }
    input.required = true;
    input.style.marginBottom = "10px";
    input.style.marginRight = "10px";

    // Restore previously selected color if available
    if (existingColors[i]) {
      input.value = existingColors[i];
    } else {
      input.value = "#000000"; // default black
    }

    colorsContainer.appendChild(label);
    colorsContainer.appendChild(input);
  }
}
    </script>



</body>

</html>