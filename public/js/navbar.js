const burger = document.querySelector(".burger");
const sidebar = document.getElementById("sidebar");
const sidebarOverlay = document.getElementById("sidebarOverlay");
const sidebarClose = document.getElementById("sidebarClose");
const dropdowns = document.querySelectorAll(".sidebar-dropdown");

burger.addEventListener("click", () => {
    sidebar.classList.add("show");
    sidebarOverlay.classList.add("show");
});

sidebarClose.addEventListener("click", () => {
    sidebar.classList.remove("show");
    sidebarOverlay.classList.remove("show");
});

sidebarOverlay.addEventListener("click", () => {
    sidebar.classList.remove("show");
    sidebarOverlay.classList.remove("show");
});
dropdowns.forEach((drop) => {
    const toggle = drop.querySelector(".sidebar-toggle");
    const submenu = drop.querySelector(".sidebar-submenu");
    toggle.addEventListener("click", () => {
        const isOpen = drop.classList.contains("active");
        dropdowns.forEach((otherDrop) => {
            const otherSubmenu = otherDrop.querySelector(".sidebar-submenu");
            if (otherDrop !== drop) {
                otherDrop.classList.remove("active");
                otherSubmenu.style.maxHeight = null;
            }
        });
        if (isOpen) {
            drop.classList.remove("active");
            submenu.style.maxHeight = null;
        } else {
            drop.classList.add("active");
            submenu.style.maxHeight = submenu.scrollHeight + "px";
        }
    });
});
function setupLiveSearch(inputId, resultsId) {
    const input = document.getElementById(inputId);
    const resultsBox = document.getElementById(resultsId);
    let timer;

    input.addEventListener("input", () => {
        clearTimeout(timer);
        const query = input.value;

        if (query.length < 2) {
            resultsBox.innerHTML = "";
            resultsBox.style.display = "none";
            return;
        }

        timer = setTimeout(() => {
            fetch(`/search-products?q=${encodeURIComponent(query)}`)
                .then((response) => response.json())
                .then((data) => {
                    if (!Array.isArray(data) || data.length === 0) {
                        resultsBox.innerHTML = "";
                        resultsBox.style.display = "none";
                        return;
                    }

                    let resultsHtml = data
                        .map((product) => {
                            const priceHtml =
                                product.sale !== null
                                    ? `<span class="product-sale">$${parseFloat(
                                          product.sale
                                      ).toFixed(2)}</span>
                                   <span class="product-regular strikethrough">$${parseFloat(
                                       product.price
                                   ).toFixed(2)}</span>`
                                    : `<span class="product-price-search">$${parseFloat(
                                          product.price
                                      ).toFixed(2)}</span>`;

                            const saleBadge =
                                product.sale !== null
                                    ? `<div class="sale-right"><span class="sale-badge-search">SALE</span></div>`
                                    : "";

                            return `
                                <div class="result-item" onclick="selectProduct('${product.id}')">
                                    <img src="/storage/products/${product.image}" alt="${product.name}" class="result-img" />
                                    <div class="result-info">
                                        <span class="product-name">${product.name}</span>
                                        ${priceHtml}
                                    </div>
                                    ${saleBadge}
                                </div>`;
                        })
                        .join("");

                    resultsBox.innerHTML = resultsHtml;
                    resultsBox.style.display = "block";
                });
        }, 300); // debounce delay
    });
}

function selectProduct(id) {
    window.location.href = "/product/" + id;
}

// Initialize for both desktop and mobile
setupLiveSearch("search", "search-results");
setupLiveSearch("mobile-search", "mobile-search-results");
