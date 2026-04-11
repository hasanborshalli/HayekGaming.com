let bannerIdToDelete = null;
let buttonClicked = null;

function deleteBanner(id, button) {
    bannerIdToDelete = id;
    buttonClicked = button;
    document.getElementById("confirmOverlay").style.display = "flex";
}

// Close modal
document.getElementById("confirmNo").addEventListener("click", () => {
    document.getElementById("confirmOverlay").style.display = "none";
    bannerIdToDelete = null;
});

// Confirm delete
document.getElementById("confirmYes").addEventListener("click", () => {
    if (!bannerIdToDelete) return;

    fetch(`/admin/deleteBanner/${bannerIdToDelete}`, {
        method: "GET",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
        },
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.status === "removed") {
                const row = buttonClicked.closest("tr");
                if (row) row.remove();
                showToast("Banner removed successfully");
            } else {
                showToast("Failed to delete the banner");
            }
        })
        .catch(() => {
            showToast("An error occurred");
        })
        .finally(() => {
            document.getElementById("confirmOverlay").style.display = "none";
            bannerIdToDelete = null;
            buttonClicked = null;
        });
});

// Toast function
function showToast(message) {
    const toast = document.getElementById("delete-toast");
    toast.textContent = message;
    toast.classList.add("show");
    setTimeout(() => {
        toast.classList.remove("show");
    }, 3000);
}
