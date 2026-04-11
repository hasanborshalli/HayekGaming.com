let index = 0;

const images = document.querySelectorAll(".image-container");
const dots = document.querySelectorAll(".dot");
const prevBanner = document.getElementById("prevBanner");
const nextBanner = document.getElementById("nextBanner");
const bannerImages = Array.from(images).map((container) => {
    const img = container.querySelector("img");
    return img ? img.src : null;
});
const track = document.querySelector(".carousel-track");
const totalImages = images.length;

function slide() {
    const currentIndex = index % totalImages;
    const prevIndex = (currentIndex - 1 + totalImages) % totalImages;
    const nextIndex = (currentIndex + 1) % totalImages;

    if (track) {
        track.style.transform = `translateX(-${currentIndex * 100}%)`;
    }

    // Update dots
    dots.forEach((dot) => dot.classList.remove("active"));
    dots[currentIndex].classList.add("active");

    // Update blurred images
    if (prevBanner && nextBanner) {
        prevBanner.src = bannerImages[prevIndex];
        nextBanner.src = bannerImages[nextIndex];
    }
}

//setInterval(slide, 3000); // Change the slider every 3 seconds
dots.forEach((dot, dotIndex) => {
    dot.addEventListener("click", () => {
        index = dotIndex;
        slide();
    });
});
let startX = 0;
let isDragging = false;

const carouselContainer = document.querySelector(".carousel-container");

carouselContainer.addEventListener("touchstart", (e) => {
    startX = e.touches[0].clientX;
    isDragging = true;
});

carouselContainer.addEventListener("touchend", (e) => {
    isDragging = false;
    const endX = e.changedTouches[0].clientX;
    const diffX = endX - startX;

    const threshold = 50;

    if (diffX > threshold) {
        index = (index - 1 + totalImages) % totalImages;
        slide();
    } else if (diffX < -threshold) {
        index = (index + 1) % totalImages;
        slide();
    }
});
