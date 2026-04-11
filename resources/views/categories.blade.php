<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" sizes="32x32" href="/img/white-logo.svg">
  <link rel="stylesheet" href="/css/fonts.css" />

    <link rel="stylesheet" href="/css/admin.css">
    <link rel="stylesheet" href="/css/productsManage.css">
    <link rel="stylesheet" href="/css/categories.css">
    <link rel="stylesheet" href="/css/pagination.css">
    <title>Manage Categories</title>
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
    <section class="manageProducts-page">
        <h1 class="page-title">Manage Categories</h1>
        <div class="add-btn">
            <button onclick="window.location.href='/admin/categories/add'">+ Add Category</button>
        </div>
        <div class="category-flex">
            @foreach ($categories as $category)
            <div class="category-card">
                <h2>{{ $category->name }}</h2>

                @if ($category->subCategories && count($category->subCategories) > 0)
                <ul class="subcategory-list">
                    @foreach ($category->subCategories as $sub)
                    <li>{{ $sub->name }}</li>
                    @endforeach
                </ul>
                @else
                <p class="no-sub">No subcategories</p>
                @endif

                <div class="card-actions">
                    <a href="/admin/categories/edit/{{ $category->id }}" class="btn edit">Edit</a>
                    <button type="submit" class="btn delete"
                        onclick="deleteCategory({{$category->id}},this,'{{$category->name}}')">Delete</button>
                </div>
            </div>
            @endforeach
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
    </section>
    <script>
        const confirmationMessage = document.getElementById("confirmation-message");
        let categoryIdToDelete = null;
        let buttonClicked = null;
        function deleteCategory(id,button,name) {
        categoryIdToDelete = id;
        buttonClicked = button; 
        confirmationMessage.textContent =
            "Are you sure you want to delete  " + name + " category?";
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

    fetch(`/admin/deleteCategory/${categoryIdToDelete}`, {
        method: "GET",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
        },
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.status === "removed") {
                const card = buttonClicked.closest(".category-card");
                if (card) card.remove();
                showToast("Category removed successfully");
            } else {
                showToast("Failed to delete the category");
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