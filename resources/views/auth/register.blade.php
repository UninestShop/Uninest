@extends('layouts.guest')

@section('content')
<div class="login-main">
  
    <div class="container">
      <div class="login-box">
        <figure><img class="wow flipInY" data-wow-delay="0.2s" src="images/login-box-img.png" alt="logo"></figure>
        <h2>Create an account</h2>
        <span>Join us to buy and sell with your university community</span>
        <form class="login-form" method="POST" action="{{ route('register') }}">
          @csrf
          <div class="row">
            <div class="col-sm-12">
              <label for="name" class="form-label">{{ __('Full Name') }}</label>
              <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                     name="name" value="{{ old('name') }}" autofocus>
              @error('name')
                  <span class="invalid-feedback cstm-error-msg" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
            </div>
            
             <!-- <div class="col-sm-12">
              <label for="university_name" class="form-label ">{{ __('Select University') }}</label>
              <select id="university_name" class="form-control @error('university_name') is-invalid @enderror" 
                     name="university_name" >
                <option value="">Select your university</option>
                @foreach($universities as $university)
                    <option value="{{ $university->id }}" {{ old('university_name') == $university->id ? 'selected' : '' }}>
                        {{ $university->name }}
                    </option>
                @endforeach
              </select>
              @error('university_name')
                  <span class="invalid-feedback cstm-error-msg" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
            </div> -->

            <div class="col-sm-12" style="position: relative;">
              <label for="university_name_input" class="form-label">{{ __('Select University') }}</label>
              <input 
              id="university_name_input" 
              type="text" 
              class="form-control @error('university_name') is-invalid @enderror" 
              autocomplete="off"
              placeholder="Type your university name..."
              value="{{ old('university_name') ? ($universities->firstWhere('id', old('university_name'))?->name ?? '') : '' }}"
              >
              <input 
              type="hidden" 
              name="university_name" 
              id="university_id_input"
              value="{{ old('university_name') }}"
              >
              <div id="university_suggestions" class="list-group" style="position: absolute; z-index: 1000; width: 100%; display: none;"></div>
              <script>
              document.addEventListener('DOMContentLoaded', function () {
              const input = document.getElementById('university_name_input');
              const suggestions = document.getElementById('university_suggestions');
              const hiddenInput = document.getElementById('university_id_input');
              const universities = [
                @foreach($universities->sortBy('name') as $university)
                { id: {{ $university->id }}, name: @json($university->name) },
                @endforeach
              ];

              input.addEventListener('input', function () {
                const query = this.value.toLowerCase();
                suggestions.innerHTML = '';
                hiddenInput.value = '';
                if (query.length === 0) {
                suggestions.style.display = 'none';
                return;
                }
                const matches = universities.filter(u => u.name.toLowerCase().startsWith(query));
                if (matches.length === 0) {
                suggestions.style.display = 'none';
                return;
                }
                matches.forEach(u => {
                const item = document.createElement('button');
                item.type = 'button';
                item.className = 'list-group-item list-group-item-action';
                item.textContent = u.name;
                item.onclick = function () {
                  input.value = u.name;
                  hiddenInput.value = u.id;
                  suggestions.style.display = 'none';
                };
                suggestions.appendChild(item);
                });
                suggestions.style.display = 'block';
              });

              document.addEventListener('click', function (e) {
                if (!input.contains(e.target) && !suggestions.contains(e.target)) {
                suggestions.style.display = 'none';
                }
              });
              });
              </script>
              @error('university_name')
              <span class="invalid-feedback cstm-error-msg" role="alert">
              <strong>{{ $message }}</strong>
              </span>
              @enderror
            </div>
            
             <div class="col-sm-12">
              <label for="university_email" class="form-label">{{ __('University Email') }}</label>
              <input id="university_email" type="email" 
                     class="form-control @error('university_email') is-invalid @enderror" 
                     name="university_email" value="{{ old('university_email') }}" >
              @error('university_email')
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
            <div class="col-sm-12 position-relative">
              <label for="password-confirm" class="form-label">{{ __('Confirm Password') }}</label>
              <div class="password-wrapper position-relative">
              <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
              <span class="password-toggle position-absolute" onclick="toggleConfirmPassword()">
                <i id="toggleConfirmPasswordIcon" class="fa-solid fa-eye"></i>
              </span>
              @error('password_confirmation')
              <span class="invalid-feedback cstm-error-msg" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
              </div>
            </div>
            <div class="col-sm-12">
              <div class="rmbr">
                <div class="rmbr-left">
                  <input type="checkbox" name="terms_accepted" id="terms_accepted"  
                         class="@error('terms_accepted') is-invalid @enderror">
                  {{-- I agree to the Terms of Service and Privacy Policy --}}
                    <label for="terms_accepted">
                    I agree to the <a href="{{ route('terms') }}">Terms of Service</a> and <a href="{{ route('privacy') }}">Privacy Policy</a>
                    </label>
                  @error('terms_accepted')
                      <span class="invalid-feedback cstm-error-msg" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>
            </div>
            <div class="col-sm-12">
              <button type="submit" class="btn btn-primary">Sign up</button>
              <span class="sign-inup">Already have an account? <a href="{{ route('login') }}">Log in</a></span>
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
@endsection
