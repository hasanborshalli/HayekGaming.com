<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Category</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/admin.css">
    <link rel="stylesheet" href="/css/editCategory.css">
</head>

<body>
    <x-navigator />
    <div class="form-container">
        <h1>Edit Category</h1>

        <form action="/admin/categories/edit/{{ $category->id }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Category Name</label>
                <input type="text" id="name" name="name" value="{{ $category->name }}" required>
            </div>

            <div class="form-group">
                <label for="slogan">Slogan</label>
                <input type="text" id="slogan" name="slogan" value="{{ $category->slogan }}">
            </div>

            <div class="form-group">
                <label>Existing Subcategories</label>
                @foreach($category->subCategories as $sub)
                <div class="existing-sub">
                    <input type="text" name="existing_subcategories[{{ $sub->id }}]" value="{{ $sub->name }}">
                    <button class="btn btn-danger" type="button"
                        onclick="deleteSub({{$sub->id}},this,'{{$sub->name}}')">Delete</button>
                </div>
                @endforeach
            </div>

            <div class="form-group">
                <label>New Subcategories</label>
                <div id="newSubContainer" class="subcategories-container">
                    <input type="text" name="new_subcategories[]" placeholder="New Subcategory">
                </div>
                <div class="sub-btns">
                    <button type="button" class="btn btn-success" onclick="addNewSub()">+ Add Subcategory</button>
                    <button type="button" class="btn btn-danger" onclick="removeNewSub()">- Remove</button>
                </div>
            </div>

            <button type="submit" class="btn btn-success">Update Category</button>
        </form>
    </div>
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
    <script>
        function addNewSub() {
            const container = document.getElementById('newSubContainer');
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'new_subcategories[]';
            input.placeholder = 'New Subcategory';
            container.appendChild(input);
        }

        function removeNewSub() {
            const container = document.getElementById('newSubContainer');
            const inputs = container.querySelectorAll('input');
            if (inputs.length > 1) {
                container.removeChild(inputs[inputs.length - 1]);
            }
        }
          const confirmationMessage = document.getElementById("confirmation-message");
        let categoryIdToDelete = null;
        let buttonClicked = null;
        function deleteSub(id,button,name) {
        categoryIdToDelete = id;
        buttonClicked = button; 
        confirmationMessage.textContent =
            "Are you sure you want to delete  " + name + " sub-category?";
        document.getElementById("confirmOverlay").style.display = "flex";
}
// Close modal
document.getElementById("confirmNo").addEventListener("click", () => {
    document.getElementById("confirmOverlay").style.display = "none";
    categoryIdToDelete = null;
});
// Confirm delete
document.getElementById("confirmYes").addEventListener("click", () => {
    if (!categoryIdToDelete) return;

    fetch(`/admin/deleteSub/${categoryIdToDelete}`, {
        method: "GET",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
        },
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.status === "removed") {
                const card = buttonClicked.closest(".existing-sub");
                if (card) card.remove();
                showToast("Sub-category removed successfully");
            } else {
                showToast("Failed to delete the sub-category");
            }
        })
        .catch(() => {
            showToast("An error occurred");
        })
        .finally(() => {
            document.getElementById("confirmOverlay").style.display = "none";
            categoryIdToDelete = null;
            buttonClicked = null;
        });
});
    </script>
</body>

</html>