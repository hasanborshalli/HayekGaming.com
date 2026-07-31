function debounce(fn, delay) {
    let t;
    return function (...args) {
        clearTimeout(t);
        t = setTimeout(() => fn.apply(this, args), delay);
    };
}

function imageUrlFor(type, image) {
    if (!image) return '';
    return '/storage/' + (type === 'watch' ? 'watches' : 'products') + '/' + image;
}

function initItemPicker(config) {
    const container = document.getElementById(config.containerId);
    const template = document.getElementById(config.templateId);
    const totalDisplay = config.totalDisplayId ? document.getElementById(config.totalDisplayId) : null;
    const addButton = document.getElementById(config.addButtonId);
    let rowCount = 0;

    function fieldName(row, field) {
        return 'items[' + row.dataset.index + '][' + field + ']';
    }

    function updateGrandTotal() {
        if (!totalDisplay) return;
        let total = 0;
        container.querySelectorAll('[data-row]').forEach((row) => {
            const qty = parseFloat(row.querySelector('[data-role="qty"]').value) || 0;
            const price = parseFloat(row.querySelector('[data-role="price"]').value) || 0;
            total += qty * price;
        });
        totalDisplay.textContent = total.toFixed(2);
    }

    function updateSubtotal(row) {
        const qty = parseFloat(row.querySelector('[data-role="qty"]').value) || 0;
        const price = parseFloat(row.querySelector('[data-role="price"]').value) || 0;
        row.querySelector('[data-role="subtotal"]').textContent = (qty * price).toFixed(2);
        updateGrandTotal();
    }

    function selectItem(row, item, type) {
        row.querySelector('[data-role="id-input"]').value = item.id;
        row.querySelector('[data-role="type-input"]').value = type;
        row.querySelector('[data-role="search"]').value = item.name;

        const resultsBox = row.querySelector('[data-role="results"]');
        resultsBox.innerHTML = '';
        resultsBox.style.display = 'none';

        const selected = row.querySelector('[data-role="selected"]');
        selected.style.display = 'flex';

        const img = row.querySelector('[data-role="selected-img"]');
        const src = imageUrlFor(type, item.image);
        if (src) {
            img.src = src;
            img.style.display = 'inline-block';
        } else {
            img.style.display = 'none';
        }

        row.querySelector('[data-role="selected-name"]').textContent = item.name;

        const stockLabel = row.querySelector('[data-role="selected-stock"]');
        if (item.stock_quantity === null || item.stock_quantity === undefined) {
            stockLabel.textContent = 'Not tracked yet';
        } else {
            stockLabel.textContent = item.stock_quantity + ' in stock';
        }

        const priceInput = row.querySelector('[data-role="price"]');
        if (config.mode === 'punch') {
            const price = item.sale !== undefined && item.sale !== null ? item.sale : item.price;
            priceInput.value = price !== undefined && price !== null ? price : '';
        }

        updateSubtotal(row);
    }

    function attachRowEvents(row) {
        const typeSelect = row.querySelector('[data-role="type"]');
        const searchInput = row.querySelector('[data-role="search"]');
        const resultsBox = row.querySelector('[data-role="results"]');
        const qtyInput = row.querySelector('[data-role="qty"]');
        const priceInput = row.querySelector('[data-role="price"]');
        const removeBtn = row.querySelector('[data-role="remove"]');
        const recordIdField = config.mode === 'purchase' ? 'purchase_item_id' : 'punch_order_item_id';

        row.querySelector('[data-role="id-input"]').name = fieldName(row, 'id');
        row.querySelector('[data-role="type-input"]').name = fieldName(row, 'type');
        row.querySelector('[data-role="record-id-input"]').name = fieldName(row, recordIdField);
        qtyInput.name = fieldName(row, 'quantity');

        if (config.mode === 'purchase') {
            priceInput.name = fieldName(row, 'unit_price');
            priceInput.readOnly = false;
        } else {
            priceInput.removeAttribute('name');
            priceInput.readOnly = true;
        }

        const doSearch = debounce(function () {
            const q = searchInput.value.trim();
            fetch(config.searchUrl + '?type=' + encodeURIComponent(typeSelect.value) + '&q=' + encodeURIComponent(q))
                .then((r) => r.json())
                .then((items) => {
                    resultsBox.innerHTML = '';
                    if (!items.length) {
                        resultsBox.style.display = 'none';
                        return;
                    }
                    items.forEach((item) => {
                        const optionEl = document.createElement('div');
                        optionEl.className = 'line-result-item';
                        const stockText = (item.stock_quantity === null || item.stock_quantity === undefined)
                            ? 'not tracked' : (item.stock_quantity + ' in stock');
                        optionEl.textContent = item.name + ' — $' + item.price + ' (' + stockText + ')';
                        optionEl.addEventListener('mousedown', (e) => {
                            e.preventDefault();
                            selectItem(row, item, typeSelect.value);
                        });
                        resultsBox.appendChild(optionEl);
                    });
                    resultsBox.style.display = 'block';
                });
        }, 250);

        searchInput.addEventListener('input', function () {
            row.querySelector('[data-role="id-input"]').value = '';
            row.querySelector('[data-role="selected"]').style.display = 'none';
            doSearch();
        });
        searchInput.addEventListener('focus', doSearch);
        searchInput.addEventListener('blur', function () {
            setTimeout(() => { resultsBox.style.display = 'none'; }, 150);
        });

        typeSelect.addEventListener('change', function () {
            row.querySelector('[data-role="id-input"]').value = '';
            searchInput.value = '';
            row.querySelector('[data-role="selected"]').style.display = 'none';
            resultsBox.innerHTML = '';
        });

        qtyInput.addEventListener('input', () => updateSubtotal(row));
        priceInput.addEventListener('input', () => updateSubtotal(row));

        removeBtn.addEventListener('click', function () {
            row.remove();
            updateGrandTotal();
        });
    }

    function addRow(prefill) {
        const clone = template.content.firstElementChild.cloneNode(true);
        clone.dataset.index = rowCount++;
        container.appendChild(clone);
        attachRowEvents(clone);

        if (prefill) {
            clone.querySelector('[data-role="type"]').value = prefill.type;
            clone.querySelector('[data-role="record-id-input"]').value = prefill.rowId;
            selectItem(clone, {
                id: prefill.id,
                name: prefill.name,
                image: prefill.image,
                price: prefill.unit_price,
                sale: null,
                stock_quantity: prefill.stock_quantity,
            }, prefill.type);
            clone.querySelector('[data-role="qty"]').value = prefill.quantity;
            clone.querySelector('[data-role="price"]').value = prefill.unit_price;
            updateSubtotal(clone);
        }

        return clone;
    }

    addButton.addEventListener('click', () => addRow());

    if (config.initialRows && config.initialRows.length) {
        config.initialRows.forEach((r) => addRow(r));
    } else {
        addRow();
    }

    updateGrandTotal();
}
