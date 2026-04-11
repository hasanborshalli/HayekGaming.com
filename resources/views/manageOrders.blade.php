<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" sizes="32x32" href="/img/white-logo.svg">
  <link rel="stylesheet" href="/css/fonts.css" />

    <link rel="stylesheet" href="/css/admin.css">
    <link rel="stylesheet" href="/css/manageOrders.css">
    <title>Manage Orders</title>
</head>

<body>
    @if (session('message'))
    <div id="toast" class="toast">{{ session('message') }}</div>
    <script>
        // Show toast for 3 seconds then fade out
        window.addEventListener('DOMContentLoaded', () => {
            const toast = document.getElementById('toast');
            toast.classList.add('show');
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        });
    </script>
    @endif
    <x-navigator />
    <section class="manageOrders-page">
        <h1 class="page-title">Manage Orders</h1>
        <div class="order-list">
            @foreach ($orders as $order)
            <x-order-card id="{{$order->id}}" name="{{$order->name}}" city="{{$order->city}}"
                price="{{$order->total}}" />

            @endforeach
        </div>

    </section>
    <div id="custom-dialog" class="dialog-overlay" style="display: none;">
        <div class="dialog-box">
            <h2>Confirmation</h2>
            <p id="dialog-text"></p>
            <div class="dialog-buttons">
                <button class="btn-confirm" id="btn-confirm">Yes</button>
                <button class="btn-cancel" onclick="closeDialog()">No</button>
            </div>
        </div>
    </div>
    <script src="/js/manageOrders.js">
    </script>
</body>

</html>