<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" sizes="32x32" href="/img/white-logo.svg">
    <link rel="stylesheet" href="/css/fonts.css" />
    <link rel="stylesheet" href="/css/admin.css">
    <link rel="stylesheet" href="/css/pagination.css">
    <link rel="stylesheet" href="/css/stockAdmin.css">
    <title>Stock</title>
</head>

<body>
    @if (session('message'))
    <div id="toast" class="toast">{{ session('message') }}</div>
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const toast = document.getElementById('toast');
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 3000);
        });
    </script>
    @endif

    <x-navigator />

    <section class="stock-page">
        <h1 class="page-title">Stock</h1>

        <div class="stock-summary">
            <div class="label">Current Stock Value (retail)</div>
            <div class="value">${{ number_format($stockValue, 2) }}</div>
        </div>

        <div class="top-bar">
            <div class="qty-edit-controls">
                <button type="button" id="adjustQtyBtn" class="adjust-qty-btn" onclick="toggleStockEdit()">Adjust Quantity</button>
                <button type="button" id="saveQtyBtn" class="submit-btn" style="display:none;" onclick="saveStockChanges()">Save Changes</button>
                <button type="button" id="cancelQtyBtn" class="clear-filter-link" style="display:none;" onclick="cancelStockEdit()">Cancel</button>
            </div>
        </div>

        <form method="GET" action="/admin/stock" class="filter-bar">
            <div class="filter-group">
                <input type="text" name="search" placeholder="Search by name..." value="{{ $search }}">
            </div>
            <div class="filter-group">
                <select name="status">
                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All</option>
                    <option value="out" {{ $status === 'out' ? 'selected' : '' }}>Out of Stock</option>
                    <option value="low" {{ $status === 'low' ? 'selected' : '' }}>Low Stock</option>
                    <option value="ok" {{ $status === 'ok' ? 'selected' : '' }}>In Stock</option>
                    <option value="untracked" {{ $status === 'untracked' ? 'selected' : '' }}>Not Tracked</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="per_page">Per page</label>
                <select name="per_page" id="per_page" onchange="this.form.submit()">
                    <option value="15" {{ $perPage === 15 ? 'selected' : '' }}>15</option>
                    <option value="50" {{ $perPage === 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ $perPage === 100 ? 'selected' : '' }}>100</option>
                    <option value="200" {{ $perPage === 200 ? 'selected' : '' }}>200</option>
                </select>
            </div>
            <div class="filter-group">
                <input type="submit" value="Apply Filter" class="filter-btn">
                @if ($search !== '' || $status !== 'all')
                <a href="/admin/stock" class="clear-filter-link">Clear</a>
                @endif
            </div>
        </form>

        <h2 class="section-heading">Products</h2>
        <div class="table-scroll">
        <table class="stock-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Stock</th>
                    <th>Threshold</th>
                    <th>Avg Cost</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                @php
                    $qty = $product->stock_quantity;
                    if ($qty === null) { $status = 'untracked'; $label = 'Not Tracked'; }
                    elseif ($qty <= 0) { $status = 'out'; $label = 'Out of Stock'; }
                    elseif ($qty <= $product->stock_threshold) { $status = 'low'; $label = 'Low Stock'; }
                    else { $status = 'ok'; $label = 'In Stock'; }
                @endphp
                <tr>
                    <td data-label="Product"><img src="/storage/products/{{ $product->image }}" alt="">{{ $product->name }}</td>
                    <td data-label="Stock">
                        <span class="qty-view">{{ $qty === null ? '—' : $qty }}</span>
                        <div class="qty-edit" style="display:none;">
                            <button type="button" class="qty-btn" onclick="stepQty(this,-1)">-</button>
                            <input type="number" class="qty-input" min="0" placeholder="—"
                                value="{{ $qty === null ? '' : $qty }}"
                                data-original="{{ $qty === null ? '' : $qty }}"
                                data-type="product" data-id="{{ $product->id }}">
                            <button type="button" class="qty-btn" onclick="stepQty(this,1)">+</button>
                        </div>
                    </td>
                    <td data-label="Threshold">
                        <span class="threshold-view">{{ $product->stock_threshold }}</span>
                        <input type="number" class="threshold-input" min="0" style="display:none;"
                            value="{{ $product->stock_threshold }}"
                            data-original="{{ $product->stock_threshold }}"
                            data-type="product" data-id="{{ $product->id }}">
                    </td>
                    <td data-label="Avg Cost">{{ $product->avg_cost !== null ? '$' . number_format($product->avg_cost, 2) : '—' }}</td>
                    <td data-label="Status"><span class="badge badge-{{ $status }}">{{ $label }}</span></td>
                </tr>
                @empty
                <tr><td colspan="5">No products found.</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
        {{ $products->links() }}

        <h2 class="section-heading">Watches & Bracelets</h2>
        <div class="table-scroll">
        <table class="stock-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Stock</th>
                    <th>Threshold</th>
                    <th>Avg Cost</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($watches as $watch)
                @php
                    $qty = $watch->stock_quantity;
                    if ($qty === null) { $status = 'untracked'; $label = 'Not Tracked'; }
                    elseif ($qty <= 0) { $status = 'out'; $label = 'Out of Stock'; }
                    elseif ($qty <= $watch->stock_threshold) { $status = 'low'; $label = 'Low Stock'; }
                    else { $status = 'ok'; $label = 'In Stock'; }
                @endphp
                <tr>
                    <td data-label="Item"><img src="/storage/watches/{{ $watch->image }}" alt="">{{ $watch->name }}</td>
                    <td data-label="Stock">
                        <span class="qty-view">{{ $qty === null ? '—' : $qty }}</span>
                        <div class="qty-edit" style="display:none;">
                            <button type="button" class="qty-btn" onclick="stepQty(this,-1)">-</button>
                            <input type="number" class="qty-input" min="0" placeholder="—"
                                value="{{ $qty === null ? '' : $qty }}"
                                data-original="{{ $qty === null ? '' : $qty }}"
                                data-type="watch" data-id="{{ $watch->id }}">
                            <button type="button" class="qty-btn" onclick="stepQty(this,1)">+</button>
                        </div>
                    </td>
                    <td data-label="Threshold">
                        <span class="threshold-view">{{ $watch->stock_threshold }}</span>
                        <input type="number" class="threshold-input" min="0" style="display:none;"
                            value="{{ $watch->stock_threshold }}"
                            data-original="{{ $watch->stock_threshold }}"
                            data-type="watch" data-id="{{ $watch->id }}">
                    </td>
                    <td data-label="Avg Cost">{{ $watch->avg_cost !== null ? '$' . number_format($watch->avg_cost, 2) : '—' }}</td>
                    <td data-label="Status"><span class="badge badge-{{ $status }}">{{ $label }}</span></td>
                </tr>
                @empty
                <tr><td colspan="5">No watches found.</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
        {{ $watches->links() }}
    </section>

    <script>
        let stockEditMode = false;

        function toggleStockEdit() {
            stockEditMode = true;
            document.querySelectorAll('.qty-view, .threshold-view').forEach(el => el.style.display = 'none');
            document.querySelectorAll('.qty-edit').forEach(el => el.style.display = 'flex');
            document.querySelectorAll('.threshold-input').forEach(el => el.style.display = 'inline-block');
            document.getElementById('adjustQtyBtn').style.display = 'none';
            document.getElementById('saveQtyBtn').style.display = 'inline-block';
            document.getElementById('cancelQtyBtn').style.display = 'inline-block';
        }

        function cancelStockEdit() {
            window.location.reload();
        }

        function stepQty(button, delta) {
            const input = button.parentElement.querySelector('.qty-input');
            const next = Math.max(0, (parseInt(input.value, 10) || 0) + delta);
            input.value = next;
        }

        function saveStockChanges() {
            const items = [];
            document.querySelectorAll('.qty-input').forEach(input => {
                // Still-untracked and untouched: never submit it (this used to be the bug —
                // it silently zeroed out every untouched, previously-untracked row on the page).
                if (input.value === '') return;

                const type = input.dataset.type;
                const id = input.dataset.id;
                const thresholdInput = document.querySelector('.threshold-input[data-type="' + type + '"][data-id="' + id + '"]');

                const qtyChanged = input.value !== input.dataset.original;
                const thresholdChanged = thresholdInput && thresholdInput.value !== thresholdInput.dataset.original;

                if (!qtyChanged && !thresholdChanged) return;

                items.push({
                    type: type,
                    id: id,
                    quantity: input.value,
                    threshold: thresholdInput ? thresholdInput.value : null,
                });
            });

            if (!items.length) {
                alert('No changes to save.');
                return;
            }

            const saveBtn = document.getElementById('saveQtyBtn');
            saveBtn.disabled = true;
            saveBtn.textContent = 'Saving...';

            fetch('/admin/stock/adjust-bulk', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ items }),
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'saved') {
                        window.location.reload();
                    } else {
                        alert(data.message || 'Failed to save changes.');
                        saveBtn.disabled = false;
                        saveBtn.textContent = 'Save Changes';
                    }
                })
                .catch(() => {
                    alert('An error occurred while saving.');
                    saveBtn.disabled = false;
                    saveBtn.textContent = 'Save Changes';
                });
        }
    </script>
</body>

</html>
