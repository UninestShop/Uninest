@extends('layouts.guest')

@section('content')
<style>
    body.for-guest-main {
        position: relative;
        height: 100vh;
    }
  </style>
    <div class="login-main">
      <div class="container">
        <div class="login-box">
          <figure><img class="wow flipInY" data-wow-delay="0.2s" src="images/login-box-img.png" alt="logo"></figure>
          <h2>Welcome back</h2>
          <span>Log in to your University account</span>

          @if(request('email'))
            <form action="{{ route('resendsignup.verify.email') }}" method="POST">
               @csrf
                <input type="hidden" name="email" value="{{ request('email', old('email')) }}" />
                <button type="submit" class="btn btn-link">Resend Verification Email</button>
            </form>
          @endif
          <form class="login-form" method="POST" action="{{ route('login') }}">
            @csrf
            @if (session('status') || session('success'))
              <div class="alert alert-success" role="alert">
                {{ session('status') ?: session('success') }}
              </div>
            @endif
            <div class="row">
              <div class="col-sm-12">
            <div class="row">
              <div class="col-sm-12">
                <label for="email">University Email</label>
                <input id="email" type="email" placeholder="email@email.edu" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email">
                @error('email')
                  <span class="invalid-feedback cstm-error-msg" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
              <div class="col-sm-12 position-relative">
                <label for="password">Password</label>
                <div class="password-wrapper position-relative">
                  <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="current-password">
                  <span class="password-toggle position-absolute" onclick="togglePassword()">
                    <i id="togglePasswordIcon" class="fa-solid fa-eye"></i>
                  </span>
                </div>
                @error('password')
                  <span class="invalid-feedback cstm-error-msg" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
              
              <div class="col-sm-12">
                <div class="rmbr">
                  <div class="rmbr-left">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember">Remember me</label>
                  </div>
                  <div class="rmbr-right">
                    <a href="{{ route('password.request') }}">Forgot Password?</a>
                  </div>
                </div>
              </div>
              <div class="col-sm-12">
                <button type="submit" class="btn btn-primary">Log in</button>
                <span class="sign-inup">Don't have an account? <a href="{{ route('register') }}">Sign up</a></span>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    
    <style>
      .password-wrapper {
        position: relative;
      }
      .password-toggle {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #6c757d;
        z-index: 10;
        transition: color 0.2s;
      }
      .password-toggle:hover {
        color: #495057;
      }
    </style>
    
    <script>
      function togglePassword() {
        const passwordField = document.getElementById('password');
        const toggleIcon = document.getElementById('togglePasswordIcon');
        
        if (passwordField.type === 'password') {
          passwordField.type = 'text';
          toggleIcon.classList.remove('fa-eye');
          toggleIcon.classList.add('fa-eye-slash');
        } else {
          passwordField.type = 'password';
          toggleIcon.classList.remove('fa-eye-slash');
          toggleIcon.classList.add('fa-eye');
        }
      }

      const productItems = document.querySelectorAll('.product-item');
        productItems.forEach(item => {
            item.style.cursor = 'pointer';
            item.addEventListener('click', function(e) {
                // Don't redirect if clicking on an actual link or button
                if (e.target.tagName === 'A' || e.target.tagName === 'BUTTON') {
                    return;
                }
                const url = this.getAttribute('data-product-url');
                if (url) {
                    window.location.href = url;
                }
            });
        });
    </script>
  
@endsection

