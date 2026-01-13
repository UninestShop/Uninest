@extends('layouts.guest')

@section('content')
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
    body.for-guest-main {
          padding-bottom: 170px;
          position: relative;
      }
      footer.footer-main.for-guest-usr {
          position: absolute;
          right: 0;
          left: 0;
          bottom: 0;
          top: auto;
      }
      @media (max-width:767px) {
        body.for-guest-main {
            padding-bottom: 0;
        }
        footer.footer-main.for-guest-usr {
            position: relative;
            right: auto;
            left: auto;
            bottom: auto;
            top: auto;
        }
      }
  </style>
<div class="login-main">
    <div class="container">
        <div class="login-box">
        <figure><img class="wow flipInY" data-wow-delay="0.2s" src="{{asset('images/forgot-password.png')}}" alt="logo"></figure>
        <h2>Set New Password</h2>
        <form  class="login-form" method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="row">
                {{-- <div class="col-sm-12">
                    <label for="password" class="col-form-label text-md-end">{{ __('New Password') }}</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-sm-12">
                    <label for="password-confirm" class="col-form-label text-md-end">{{ __('Confirm New Password') }}</label>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                </div> --}}
                <div class="col-sm-12 position-relative">
                    <label for="password">New Password</label>
                    <div class="password-wrapper position-relative">
                      <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                      <span class="password-toggle position-absolute" onclick="togglePassword()">
                        <i id="togglePasswordIcon" class="fa-solid fa-eye"></i>
                      </span>
                    </div>
                    @error('password')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>
                  <div class="col-sm-12 position-relative">
                    <label for="password-confirm" class="form-label">{{ __('Confirm New Password') }}</label>
                    <div class="password-wrapper position-relative">
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                    <span class="password-toggle position-absolute" onclick="toggleConfirmPassword()">
                      <i id="toggleConfirmPasswordIcon" class="fa-solid fa-eye"></i>
                    </span>
                    </div>
                  </div>
                <div class="col-sm-12">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Save') }}
                    </button>
                </div>
            </div>
    </form>
        </div>
    </div>
</div>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<script>
  function togglePassword() {
  const passwordInput = document.getElementById('password');
  const icon = document.getElementById('togglePasswordIcon');
  
  if (passwordInput.type === 'password') {
    passwordInput.type = 'text';
    icon.classList.remove('fa-eye');
    icon.classList.add('fa-eye-slash');
  } else {
    passwordInput.type = 'password';
    icon.classList.remove('fa-eye-slash');
    icon.classList.add('fa-eye');
  }
  }
  
  function toggleConfirmPassword() {
  const confirmPasswordInput = document.getElementById('password-confirm');
  const icon = document.getElementById('toggleConfirmPasswordIcon');
  
  if (confirmPasswordInput.type === 'password') {
    confirmPasswordInput.type = 'text';
    icon.classList.remove('fa-eye');
    icon.classList.add('fa-eye-slash');
  } else {
    confirmPasswordInput.type = 'password';
    icon.classList.remove('fa-eye-slash');
    icon.classList.add('fa-eye');
  }
  }
</script>
@endsection
