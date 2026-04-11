function addToCart(id, type) {
    let currentQuantity = document.getElementById("quantity-" + id)?.value || 1;
    const token = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
    if (currentQuantity >= 1) {
        let options = {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": token,
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                id: id,
                quantity: currentQuantity,
                type: type,
            }),
        };

        fetch("/addCart", options)
            .then((response) => response.json())
            .then((data) => {
                if (data.status === "addedNew") {
                    updateCartQuantity();
                    showToast("Added to cart");
                } else if (data.status === "addedOld") {
                    showToast("Added to cart");
                } else {
                    showToast("Error adding to cart");
                }
            });
    }
}
// Toast function
function showToast(message) {
    const toast = document.getElementById("toast");
    toast.textContent = message;
    toast.classList.add("show");
    setTimeout(() => {
        toast.classList.remove("show");
    }, 3000);
}
let cartQuantityInput = document.getElementsByClassName("cart-quantity")[0];
let cartQuantityMobileInput =
    document.getElementsByClassName("cart-quantity")[1];
let cartQuantity = cartQuantityInput.textContent;
function updateCartQuantity() {
    cartQuantity++;
    cartQuantityInput.textContent = cartQuantity;
    cartQuantityMobileInput.textContent = cartQuantity;
}
