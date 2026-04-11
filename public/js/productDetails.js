const productImage = document.querySelector(".product-img img");
const otherImages = document.querySelectorAll(".other-images img");
function changeImage(id, src) {
    const selectedImage = document.getElementById(id);
    otherImages.forEach((image) => {
        image.classList.remove("selected-image");
    });
    selectedImage.classList.add("selected-image");
    productImage.setAttribute("src", src);
}
const quantity = document.getElementsByClassName("quantity-input")[0];
function updateQuantity(sign) {
    if (sign === "+") {
        quantity.value = parseInt(quantity.value) + 1;
    } else if (sign === "-") {
        if (quantity.value > 1) {
            quantity.value = parseInt(quantity.value) - 1;
        }
    }
}
document.querySelector("#quantity").addEventListener("input", function () {
    document.querySelector("#buyNowQuantity").value = this.value;
});
