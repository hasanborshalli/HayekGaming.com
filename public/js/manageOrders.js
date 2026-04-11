let orderIdToDelete = null;
const dialog = document.getElementById("custom-dialog");
const dialogText = document.getElementById("dialog-text");

function openDialog(id, name) {
    dialogText.textContent =
        "Are you sure you want to delete " + name + " order?";
    orderIdToDelete = id;
    dialog
        .querySelector(".btn-confirm")
        .setAttribute("onclick", "deleteOrder()");

    dialog.style.display = "flex";
}

function closeDialog() {
    dialog.style.display = "none";
}
function deleteOrder() {
    if (orderIdToDelete === null) {
        return;
    }

    let options = {
        method: "GET",
        headers: {
            "X-CSRF-TOKEN": "{{csrf_token()}}",
            "Content-Type": "application/json",
        },
    };

    fetch("/order/delete/" + orderIdToDelete, options)
        .then((response) => response.json())
        .then((data) => {
            if (data.status === "removed") {
                // You can also optionally remove the order card from the DOM after deletion
                const orderCard = document.querySelector(
                    `[order-id='${orderIdToDelete}']`
                );
                if (orderCard) {
                    orderCard.remove();
                }
                closeDialog();
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            closeDialog();
        });
}
