@extends('layouts.guest')

<style>
      body.for-guest-main {
          padding-bottom: 170px;
          position: relative;
          height: 100vh;
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
@section('content')
    <div class="login-main">
      <div class="container">
        <div class="login-box">
          <figure><img class="wow flipInY" data-wow-delay="0.2s" src="{{asset('images/forgot-password.png')}}" alt="logo"></figure>
          <h2>Forgot Password?</h2>
          <span>No worries, we will send you the reset instructions link to your email</span>
          
          @if (session('success'))
              <div class="alert alert-success" role="alert">
                  {{ session('success') }}
              </div>
          @endif
          
          @if (session('status'))
              <div class="alert alert-success" role="alert">
                  {{ session('status') }}
              </div>
          @endif
          
          <form class="login-form" method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="row">
              <div class="col-sm-12">
                <label for="email">Email</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Enter your Email Address">
                @error('email')
                    <span class="invalid-feedback cstm-error-msg" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>
              <div class="col-sm-12">
                <button type="submit" class="btn btn-primary">Send Password Reset Link</button>
                <span class="sign-inup"><a href="{{ route('login') }}">Back to Login</a></span>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
@endsection


