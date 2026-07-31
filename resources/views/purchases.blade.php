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
    <title>Purchases</title>
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

    <section class="purchases-page">
        <h1 class="page-title">Purchases (Stock In)</h1>

        <div class="top-bar">
            <div class="add-btn">
                <button onclick="window.location.href='/admin/stock/purchases/add'">+ New Purchase</button>
            </div>
        </div>

        <form method="GET" action="/admin/stock/purchases" class="filter-bar">
            <div class="filter-group">
                <label for="search">Search</label>
                <input type="text" id="search" name="search" placeholder="Note or product/watch name..." value="{{ $search }}">
            </div>
            <div class="filter-group">
                <label for="from">From</label>
                <input type="date" id="from" name="from" value="{{ $from }}">
            </div>
            <div class="filter-group">
                <label for="to">To</label>
                <input type="date" id="to" name="to" value="{{ $to }}">
            </div>
            <div class="filter-group">
                <input type="submit" value="Apply Filter" class="filter-btn">
            </div>
            @if ($search !== '' || $from || $to)
            <a href="/admin/stock/purchases" class="clear-filter-link">Clear</a>
            @endif
        </form>

        <div class="table-scroll">
        <table class="record-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Note</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($purchases as $purchase)
                <tr>
                    <td data-label="#">{{ $purchase->id }}</td>
                    <td data-label="Note">{{ $purchase->note ?? '—' }}</td>
                    <td data-label="Items">{{ $purchase->items()->count() }}</td>
                    <td data-label="Total">${{ number_format($purchase->total, 2) }}</td>
                    <td data-label="Date">{{ $purchase->created_at->format('Y-m-d H:i') }}</td>
                    <td data-label="Actions">
                        <button class="edit-btn" onclick="window.location.href='/admin/stock/purchases/edit/{{ $purchase->id }}'">Edit</button>
                        <button class="delete-btn" onclick="deleteRecord({{ $purchase->id }}, this)">Delete</button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6">No purchases recorded yet.</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
        {{ $purchases->links() }}
    </section>

    <div id="confirmOverlay" class="overlay" style="display: none;">
        <div class="confirm-box">
            <p id="confirmation-message">Are you sure you want to delete this purchase? This will reverse its effect on stock.</p>
            <div class="buttons">
                <button class="btn red" id="confirmYes">Yes</button>
                <button class="btn" id="confirmNo">No</button>
            </div>
        </div>
    </div>

    <script>
        function showToast(message) {
            const toast = document.getElementById('toast') || (function () {
                const el = document.createElement('div');
                el.id = 'toast';
                el.className = 'toast';
                document.body.appendChild(el);
                return el;
            })();
            toast.textContent = message;
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 3000);
        }

        let recordIdToDelete = null;
        let rowToRemove = null;

        function deleteRecord(id, button) {
            recordIdToDelete = id;
            rowToRemove = button.closest('tr');
            document.getElementById('confirmOverlay').style.display = 'flex';
        }

        document.getElementById('confirmNo').addEventListener('click', () => {
            document.getElementById('confirmOverlay').style.display = 'none';
            recordIdToDelete = null;
        });

        document.getElementById('confirmYes').addEventListener('click', () => {
            if (!recordIdToDelete) return;
            fetch(`/admin/stock/purchases/delete/${recordIdToDelete}`, {
                method: 'GET',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.status === 'removed') {
                        if (rowToRemove) rowToRemove.remove();
                        showToast('Purchase deleted successfully');
                    } else {
                        showToast(data.message || 'Failed to delete this purchase');
                    }
                })
                .catch(() => showToast('An error occurred'))
                .finally(() => {
                    document.getElementById('confirmOverlay').style.display = 'none';
                    recordIdToDelete = null;
                    rowToRemove = null;
                });
        });
    </script>
</body>

</html>
