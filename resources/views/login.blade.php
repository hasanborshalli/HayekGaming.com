<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="icon" sizes="32x32" href="/img/white-logo.svg">
  <link rel="stylesheet" href="/css/login.css">
  <title>Login Page</title>
</head>

<body>
  <div class="login-wrapper">
    <div class="login-form">
      <a href="/" class="logo-link">
        <img src="/img/colored-logo.webp" alt="Hayek Gaming Logo" class="logo" loading="lazy" />
      </a>
      <h1>Admin Login</h1>
      @if(session('error'))
      <h3 style="color:red">{{session('error')}}</h3>
      @endif
      <form method="POST" action="/admin/login">
        @csrf
        <div class="form-group">
          <label for="email">Email Address</label>
          <input type="email" name="email" id="email" required value="{{old( 'email')}}" />
          @error('email')
          <p style="color:red">{{$message}}</p>
          @enderror
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" name="password" id="password" required />
          @error('password')
          <p style="color:red">{{$message}}</p>
          @enderror
        </div>
        <button type="submit">Login</button>
      </form>
    </div>
  </div>
</body>

</html>