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
    <title>Edit Watch</title>
    <style>
        .img-preview {
            display: block;
        }
    </style>
</head>

<body>
    <x-navigator />
    <section class="addProduct-page">
        <h1 class="page-title">Edit {{$watch->name}}</h1>

        <form method="POST" enctype="multipart/form-data" action="/admin/editWatch/{{$watch->id}}">
            @csrf

            <div class="form-row">

                <div class="form-group">
                    <label for="type">Type</label>
                    <select id="type" required name="type_id">
                        <option value="" selected disabled>Select</option>
                        @foreach ($types as $type )
                        <option value="{{$type->id}}" {{ old('type_id', $watch->type_id) == $type->id
                            ? 'selected'
                            : '' }}>{{$type->name}}</option>
                        @endforeach
                    </select>

                    @error('type_id')
                    <p style="color:red">{{$message}}</p>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Name (20 characters max)</label>
                        <input type="text" id="smallName" required name="name" value="{{old('name',$watch->name)}}">
                        @error('name')
                        <p style="color:red">{{$message}}</p>
                        @enderror
                    </div>


                    <div class="form-row">
                        <div class="form-group">
                            <label for="price">Price ($)</label>
                            <input type="decimal" id="price" required name="price"
                                value="{{old('price',$watch->price)}}">
                            @error('price')
                            <p style="color:red">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="salePrice">Sale Price ($) <small style="color: gray;">(optional)</small></label>
                            <input type="decimal" id="salePrice" name="sale" value="{{ old('sale',$watch->sale) }}">
                            @error('sale')
                            <p style="color:red">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="cost">Cost ($)</label>
                            <input type="decimal" id="cost" name="cost" value="{{ old('cost',$watch->cost) }}">
                            @error('cost')
                            <p style="color:red">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="isAvailable">Is Available</label>
                            <select id="isAvailable" required name="is_available">
                                <option value="yes" {{ old('is_available', $watch->is_available) == 1 ? 'selected' :
                                    '' }}>Yes
                                </option>
                                <option value="no" {{ old('is_available', $watch->is_available) == 0 ? 'selected' : ''
                                    }}>No</option>
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
                                <option value="no" {{ old('featured', $watch->featured) == 0 ? 'selected' : '' }}>No
                                </option>
                                <option value="yes" {{ old('featured', $watch->featured) == 1 ? 'selected' : '' }}>Yes
                                </option>
                            </select>
                            @error('featured')
                            <p style="color:red">{{$message}}</p>
                            @enderror
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="smallDesc">Small Description</label>
                        <textarea id="smallDesc" rows="3"
                            name="description">{{old('description' ,$watch->description)}}</textarea>
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
                        <textarea id="boxContents" rows="4"
                            name="box_contents">{{old('box_contents',$boxContents)}}</textarea>
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
                        <img id="preview-mainImage" class="img-preview" src="/storage/watches/{{$watch->image}}"
                            loading="lazy" />
                    </div>

                    <div class="form-row">
                        <div class="form-group image-upload">
                            <label for="img1">Image 1</label>
                            <input type="file" id="img1" accept="image/*" name="image1">
                            @error('image1')
                            <p style="color:red">{{$message}}</p>
                            @enderror
                            <img id="preview-img1" class="img-preview" src="/storage/watches/{{$watch->image1}}"
                                loading="lazy" />
                        </div>
                        <div class="form-group image-upload">
                            <label for="img2">Image 2</label>
                            <input type="file" id="img2" accept="image/*" name="image2">
                            @error('image2')
                            <p style="color:red">{{$message}}</p>
                            @enderror
                            <img id="preview-img2" class="img-preview" src="/storage/watches/{{$watch->image2}}"
                                loading="lazy" />
                        </div>
                        <div class="form-group image-upload">
                            <label for="img3">Image 3</label>
                            <input type="file" id="img3" accept="image/*" name="image3">
                            @error('image3')
                            <p style="color:red">{{$message}}</p>
                            @enderror
                            <img id="preview-img3" class="img-preview" src="/storage/watches/{{$watch->image3}}"
                                loading="lazy" />
                        </div>
                        <div class="form-group image-upload">
                            <label for="img4">Image 4</label>
                            <input type="file" id="img4" accept="image/*" name="image4">
                            @error('image4')
                            <p style="color:red">{{$message}}</p>
                            @enderror
                            <img id="preview-img4" class="img-preview" style="max-width:400px;"
                                src="/storage/watches/{{$watch->image4}}" loading="lazy" />
                        </div>
                        <div class="form-group image-upload">
                            <label for="img5">Image 5</label>
                            <input type="file" id="img5" accept="image/*" name="image5">
                            @error('image5')
                            <p style="color:red">{{$message}}</p>
                            @enderror
                            <img id="preview-img5" class="img-preview" style="max-width:400px;"
                                src="/storage/watches/{{$watch->image5}}" loading="lazy" />
                        </div>
                        <div class="form-group image-upload">
                            <label for="img6">Image 6</label>
                            <input type="file" id="img6" accept="image/*" name="image6">
                            @error('image4')
                            <p style="color:red">{{$message}}</p>
                            @enderror
                            <img id="preview-img6" class="img-preview" style="max-width:400px;"
                                src="/storage/watches/{{$watch->image6}}" loading="lazy" />
                        </div>
                    </div>
                    <div id="colorInputsContainer">
                        <h3 style="margin-bottom: 10px;">Watch Colors</h3>

                        @php
                        $colors = [
                        'color' => $watch->color ?? '',
                        'color1' => $watch->color1 ?? '',
                        'color2' => $watch->color2 ?? '',
                        'color3' => $watch->color3 ?? '',
                        'color4' => $watch->color4 ?? '',
                        'color5' => $watch->color5 ?? '',
                        'color6' => $watch->color6 ?? '',
                        ];
                        @endphp

                        @foreach ($colors as $key => $value)
                        @if(!empty($value))
                        <div class="form-group color-group" data-related="{{ $key }}">
                            <label for="{{ $key }}">Color {{ $loop->index + 1 }}</label>
                            <input type="color" id="{{ $key }}" name="{{ $key }}" value="{{ old($key, $value) }}">
                        </div>
                        @endif
                        @endforeach
                    </div>


                    <input type="submit" value="Edit Watch" class="submit-btn" />
                </div>
        </form>
    </section>
    <script>
        const imageInputs = ['mainImage', 'img1', 'img2', 'img3', 'img4', 'img5', 'img6'];
const colorInputsContainer = document.getElementById('colorInputsContainer');

// show previews and handle color input creation
imageInputs.forEach((id, index) => {
  const input = document.getElementById(id);
  const preview = document.getElementById('preview-' + id);
  const colorKey = index === 0 ? 'color' : 'color' + index;

  input.addEventListener('change', function () {
    const file = input.files[0];

    // ✅ update preview
    if (file) {
      const reader = new FileReader();
      reader.onload = function (e) {
        preview.src = e.target.result;
        preview.style.display = 'block';
      };
      reader.readAsDataURL(file);

      // ✅ check if color input already exists
      let existingColor = colorInputsContainer.querySelector(`[data-related="${colorKey}"]`);
      if (!existingColor) {
        const wrapper = document.createElement('div');
        wrapper.classList.add('form-group', 'color-group');
        wrapper.setAttribute('data-related', colorKey);

        const label = document.createElement('label');
        label.setAttribute('for', colorKey);
        label.textContent = `Color ${index + 1}`;

        const colorInput = document.createElement('input');
        colorInput.type = 'color';
        colorInput.id = colorKey;
        colorInput.name = colorKey;
        colorInput.value = '#000000';

        wrapper.appendChild(label);
        wrapper.appendChild(colorInput);
        colorInputsContainer.appendChild(wrapper);
      }

    } else {
      // if image removed, remove color input too
      let existingColor = colorInputsContainer.querySelector(`[data-related="${colorKey}"]`);
      if (existingColor) existingColor.remove();
      preview.src = '';
      preview.style.display = 'none';
    }
  });
});
    </script>


</body>

</html>