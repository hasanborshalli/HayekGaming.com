<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" sizes="32x32" href="/img/white-logo.svg">
  <link rel="stylesheet" href="/css/fonts.css" />

    <link rel="stylesheet" href="/css/admin.css">
    <link rel="stylesheet" href="/css/addProduct.css">
    <title>Manage Moving Sentence</title>
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
    <section class="addProduct-page">
        <h1 class="page-title">Manage Moving Sentence</h1>

        <form method="POST" action="/admin/changeSentence">
            @csrf
            <div class="current">
                <h3>Current Moving Sentence</h3>
                <p>{{$sentence}}</p>
            </div>
            <div class="form-row">
                <div class="form-row">
                    <div class="form-group">
                        <label for="sentence">New Moving Sentence</label>
                        <input type="text" id="sentence" required name="sentence" value="{{old('sentence')}}">
                        @error('sentence')
                        <p style="color:red">{{$message}}</p>
                        @enderror
                    </div>
                    <input type="submit" value="Change Moving Sentence" class="submit-btn" />
                </div>
        </form>
    </section>
</body>

</html>