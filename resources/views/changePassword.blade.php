<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="icon" sizes="32x32" href="/img/white-logo.svg">
  <link rel="stylesheet" href="/css/fonts.css" />

  <link rel="stylesheet" href="/css/admin.css">
  <link rel="stylesheet" href="/css/changePassword.css">
  <title>Change Password</title>
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
  <section class="change-password-page">
    <h1 class="page-title">Change Password</h1>

    <div class="form-container">
      <form id="passwordForm" method="POST" action="/admin/changePassword">
        @csrf
        <label for="oldPassword">Old Password</label>
        <input type="password" id="oldPassword" name="oldPassword" required>
        @error('oldPassword')
        <p style="color:red">{{$message}}</p>
        @enderror
        <label for="newPassword">New Password</label>
        <input type="password" id="newPassword" name="password" required>
        @error('password')
        <p style="color:red">{{$message}}</p>
        @enderror

        <label for="confirmPassword">Confirm New Password</label>
        <input type="password" id="confirmPassword" name="password_confirmation" required>
        @error('password_confirmation')
        <p style="color:red">{{$message}}</p>
        @enderror


        <input type="submit" class="btn" value="Change Password">
      </form>
    </div>
  </section>
</body>

</html>