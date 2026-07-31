<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" sizes="32x32" href="/img/white-logo.svg">
    <link rel="stylesheet" href="/css/fonts.css" />
    <link rel="stylesheet" href="/css/admin.css">
    <link rel="stylesheet" href="/css/stockAdmin.css">
    <title>Punch New Order</title>
</head>

<body>
    <x-navigator />

    <section class="punch-order-form-page">
        <h1 class="page-title">Punch New Order (In-Store Sale)</h1>

        @if ($errors->any())
        <div class="error-box">
            @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <form method="POST" action="/admin/stock/punch-orders/add" id="punchOrderForm">
            @csrf

            <div class="form-group">
                <label for="note">Note (optional)</label>
                <input type="text" id="note" name="note" value="{{ old('note') }}">
            </div>

            <div class="line-items" id="lineItemsContainer"></div>
            <button type="button" class="add-line-btn" id="addLineBtn">+ Add Line</button>

            <div class="grand-total">Receipt Total: $<span id="grandTotal">0.00</span></div>

            <button type="submit" class="submit-btn">Punch Order</button>
        </form>
    </section>

    <template id="lineRowTemplate">
        <div class="line-row" data-row>
            <select data-role="type">
                <option value="product">Product</option>
                <option value="watch">Watch/Bracelet</option>
            </select>
            <div class="line-search-wrap">
                <input type="text" data-role="search" placeholder="Search by name..." autocomplete="off">
                <div class="line-results" data-role="results"></div>
                <div class="line-selected" data-role="selected">
                    <img data-role="selected-img" src="" alt="">
                    <span data-role="selected-name"></span>
                    <span data-role="selected-stock"></span>
                </div>
            </div>
            <input type="number" data-role="qty" min="1" value="1" placeholder="Qty">
            <input type="number" data-role="price" min="0" step="0.01" placeholder="Price" readonly>
            <span class="line-subtotal" data-role="subtotal">0.00</span>
            <button type="button" class="line-remove" data-role="remove">✕</button>
            <input type="hidden" data-role="id-input">
            <input type="hidden" data-role="type-input">
            <input type="hidden" data-role="record-id-input">
        </div>
    </template>

    <script src="/js/itemPicker.js"></script>
    <script>
        initItemPicker({
            mode: 'punch',
            containerId: 'lineItemsContainer',
            templateId: 'lineRowTemplate',
            addButtonId: 'addLineBtn',
            totalDisplayId: 'grandTotal',
            searchUrl: '/admin/stock/search-items',
            initialRows: [],
        });
    </script>
</body>

</html>
