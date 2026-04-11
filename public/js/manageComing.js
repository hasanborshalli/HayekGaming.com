const imageInput = document.getElementById("imageInput");
const comingSoonImg = document.getElementById("comingSoonImg");
imageInput.addEventListener("change", function () {
    const file = this.files[0];
    if (file && file.type.startsWith("image/")) {
        const reader = new FileReader();
        reader.onload = function (e) {
            comingSoonImg.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});

// Submit form when file is selected

let productIdToDelete = null;
let buttonClicked = null;
const confirmationMessage = document.getElementById("confirmation-message");

function deleteProduct(id, button) {
    productIdToDelete = id;
    buttonClicked = button;
    confirmationMessage.textContent = `Are you sure you want to delete this product?`;
    document.getElementById("confirmOverlay").style.display = "flex";
}

document.getElementById("confirmNo").addEventListener("click", () => {
    document.getElementById("confirmOverlay").style.display = "none";
    productIdToDelete = null;
    buttonClicked = null;
});

document.getElementById("confirmYes").addEventListener("click", () => {
    if (!productIdToDelete) return;

    fetch(`/admin/comingSoon/deleteProduct/${productIdToDelete}`, {
        method: "GET",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
        },
    })
        .then((res) => res.json())
        .then((data) => {
            if (data.status === "removed") {
                const card = buttonClicked.closest(".coming-card");
                if (card) card.remove();
                showToast("Product removed successfully");
            } else {
                showToast("Failed to delete the product");
            }
        })
        .catch(() => showToast("An error occurred"))
        .finally(() => {
            document.getElementById("confirmOverlay").style.display = "none";
            productIdToDelete = null;
            buttonClicked = null;
        });
});

function showToast(message) {
    const toast = document.getElementById("delete-toast");
    toast.textContent = message;
    toast.classList.add("show");
    setTimeout(() => toast.classList.remove("show"), 3000);
}
const addForm = document.getElementById("add-form");
function addProduct() {
    if (addForm.style.display === "none" || addForm.style.display === "") {
        addForm.style.display = "block";
    } else {
        addForm.style.display = "none";
    }
}
function toggleEditForm(id) {
    const form = document.getElementById(`edit-form-${id}`);
    form.style.display = form.style.display === "block" ? "none" : "block";
}
