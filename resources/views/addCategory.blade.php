<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Category</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" sizes="32x32" href="/img/white-logo.svg">

    <link rel="stylesheet" href="/css/admin.css">
    <link rel="stylesheet" href="/css/addCategory.css">
</head>

<body>
    <x-navigator />
    <div class="form-container">
        <h1>Add Category</h1>

        <form action="/admin/addCategory" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Category Name</label>
                <input type="text" id="name" name="name" required>
                @error('name')
                <p style="color:red">{{$message}}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="slogan">Slogan</label>
                <input type="text" id="slogan" name="slogan" required>
                @error('slogan')
                <p style="color:red">{{$message}}</p>
                @enderror
            </div>

            <div class="form-group">
                <label>Subcategories</label>
                <div id="subcategories" class="subcategories-container">
                    <input type="text" name="subcategories[]" placeholder="Subcategory Name">
                </div>
                <div class="sub-btns">
                    <button type="button" class="btn" onclick="addSubcategory()">+ Add Subcategory</button>
                    <button type="button" class="btn btn-danger" onclick="removeSubcategory()">- Remove</button>
                </div>
            </div>
            @error('subcategories')
            <p style="color:red">{{$message}}</p>
            @enderror
            <button type="submit" class="btn">Add Category</button>
        </form>
    </div>

    <script>
        function addSubcategory() {
            const container = document.getElementById('subcategories');
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'subcategories[]';
            input.placeholder = 'Subcategory Name';
            container.appendChild(input);
        }

        function removeSubcategory() {
            const container = document.getElementById('subcategories');
            const inputs = container.querySelectorAll('input');
            if (inputs.length > 1) {
                container.removeChild(inputs[inputs.length - 1]);
            }
        }
    </script>
</body>

</html>