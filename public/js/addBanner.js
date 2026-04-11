document.addEventListener("DOMContentLoaded", function () {
    const desktopInput = document.getElementById("desktopImage");
    const desktopPreview = document.getElementById("desktopPreview");

    const mobileInput = document.getElementById("mobileImage");
    const mobilePreview = document.getElementById("mobilePreview");

    const smallInput = document.getElementById("smallImage");
    const smallPreview = document.getElementById("smallPreview");

    desktopInput.addEventListener("change", function () {
        const file = this.files[0];
        if (file) {
            desktopPreview.src = URL.createObjectURL(file);
            desktopPreview.style.display = "block";
        }
    });

    mobileInput.addEventListener("change", function () {
        const file = this.files[0];
        if (file) {
            mobilePreview.src = URL.createObjectURL(file);
            mobilePreview.style.display = "block";
        }
    });
    smallInput.addEventListener("change", function () {
        const file = this.files[0];
        if (file) {
            smallPreview.src = URL.createObjectURL(file);
            smallPreview.style.display = "block";
        }
    });
});

function previewImage(inputElement, previewElement) {
    const file = inputElement.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            previewElement.src = e.target.result;
            previewElement.style.display = "block";
        };
        reader.readAsDataURL(file);
    }
}

desktopInput.addEventListener("change", () =>
    previewImage(desktopInput, desktopPreview)
);
mobileInput.addEventListener("change", () =>
    previewImage(mobileInput, mobilePreview)
);
smallInput.addEventListener("change", () =>
    previewImage(smallInput, smallPreview)
);
