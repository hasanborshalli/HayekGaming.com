document.addEventListener("DOMContentLoaded", function () {
    const categorySelect = document.getElementById("categoryFilter");
    const subcategorySelect = document.getElementById("subcategoryFilter");
    const gameTypeDiv = document.getElementById("gameTypeCheckboxes");

    categorySelect.addEventListener("change", function () {
        const categoryId = this.value;
        if (categoryId == 0) {
            subcategorySelect.innerHTML =
                '<option value="">All Subcategories</option>';
            return;
        } else {
            subcategorySelect.innerHTML =
                '<option value="">Loading...</option>';

            fetch(`/get-subcategories/${categoryId}`)
                .then((response) => response.json())
                .then((data) => {
                    console.log(data);
                    subcategorySelect.innerHTML =
                        '<option value="">All Subcategories</option>';
                    data.forEach((sub) => {
                        const option = document.createElement("option");
                        option.value = sub.id;
                        option.textContent = sub.name;
                        subcategorySelect.appendChild(option);
                    });
                });
        }
    });

    subcategorySelect.addEventListener("change", function () {
        const selected = this.options[this.selectedIndex].text.toLowerCase();
        if (selected === "games") {
            gameTypeDiv.style.display = "block";
        } else {
            gameTypeDiv.style.display = "none";
        }
    });
});

let productIdToDelete = null;
let buttonClicked = null;
const confirmationMessage = document.getElementById("confirmation-message");
function deleteProduct(id, button, name, type) {
    productIdToDelete = id;
    buttonClicked = button;
    confirmationMessage.textContent =
        "Are you sure you want to delete this " + name + "?";
    document.getElementById("confirmOverlay").style.display = "flex";
    productTypeToDelete = type;
}
// Close modal
document.getElementById("confirmNo").addEventListener("click", () => {
    document.getElementById("confirmOverlay").style.display = "none";
    productIdToDelete = null;
});
// Confirm delete
document.getElementById("confirmYes").addEventListener("click", () => {
    if (!productIdToDelete) return;
    const route =
        productTypeToDelete === "watch"
            ? `/admin/deleteWatch/${productIdToDelete}`
            : `/admin/deleteProduct/${productIdToDelete}`;
    fetch(route, {
        method: "GET",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
        },
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.status === "removed") {
                const card = buttonClicked.closest(".product-card");
                if (card) card.remove();
                showToast("Product removed successfully");
            } else {
                showToast("Failed to delete the product");
            }
        })
        .catch(() => {
            showToast("An error occurred");
        })
        .finally(() => {
            document.getElementById("confirmOverlay").style.display = "none";
            productIdToDelete = null;
            buttonClicked = null;
        });
});
