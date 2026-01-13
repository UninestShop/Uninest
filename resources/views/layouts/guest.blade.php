<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/favicon.png') }}" sizes="16x16 32x32" type="image/png">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/animate.css') }}">

    <title>UniNest</title>
    <style>
      /* Fix for search section */
      .search-section form {
        display: flex;
        width: 100%;
      }
      .search-section form select,
      .search-section form input,
      .search-section form button {
        margin-right: 5px;
      }
      .search-section form button {
        margin-right: 0;
      }
      
      /* Sidebar styles */
      .main-container {
        display: flex;
        min-height: calc(100vh - 200px);
      }
      .sidebar-wrapper {
        width: 280px;
        background: #f8f9fa;
        padding: 20px;
        border-right: 1px solid #e3e3e3;
      }
      .profile-top {
        display: flex;
        flex-direction: column;
      }
      .profile-name {
        text-align: center;
        margin-bottom: 25px;
      }
      .profile-name figure {
        width: 100px;
        height: 100px;
        margin: 0 auto 15px;
        border-radius: 50%;
        overflow: hidden;
      }
      .profile-name figure img {
        width: 100%;
        height: 100%;
        object-fit: cover;
      }
      .profile-name h2 {
        font-size: 20px;
        margin-bottom: 5px;
      }
      .profile-name p {
        color: #6A6F74;
        margin-bottom: 0;
      }
      .profile-list {
        list-style: none;
        padding: 0;
        margin: 0;
      }
      .profile-list li {
        margin-bottom: 10px;
      }
      .pro-links {
        display: flex;
        align-items: center;
        color: #6A6F74;
        text-decoration: none;
        padding: 10px 15px;
        border-radius: 5px;
        transition: all 0.3s ease;
        background: transparent;
        width: 100%;
        text-align: left;
        border: none;
      }
      .pro-links svg {
        margin-right: 10px;
      }
      .pro-links:hover {
        background: #e9ecef;
        color: #212529;
      }
      .pro-links.active {
        background: #007bff;
        color: white;
      }
      .pro-links.active svg path {
        fill: white;
      }
      .btn-link {
        cursor: pointer;
      }
      .content-section {
        flex-grow: 1;
        padding: 20px;
      }
      
      
      /* Responsive styles */
      @media (max-width: 768px) {
        .main-container {
          flex-direction: column;
        }
        .sidebar-wrapper {
          width: 100%;
          border-right: none;
          border-bottom: 1px solid #e3e3e3;
        }
      }
    </style>
  </head>
  <body class="for-guest-main">
    
    <header>
      <div class="header-top my-profile-space">
        <div class="container">
          <div class="header-main">
            <div class="logo">
              <a href="{{ url('/') }}"><img class="wow flipInY" data-wow-delay="0.2s" src="{{ asset('images/university-logo.svg') }}" alt="logo"></a>
            </div>
            {{-- <div class="search-section">
              <form action="#" method="GET" id="searchForm">
                <select class="form-control" name="category" id="categorySelect">
                  <option value="">Search by Category</option>
                </select>
                <input type="text" name="search" id="searchInput" class="form-control" placeholder="Search item..." value="">
                <button type="#" class="btn btn-primary">Search</button>
              </form>
            </div> --}}
            <div class="head-top-right">
              @guest
                <a href="{{ route('login') }}" class="btn btn-primary"><img class="wow flipInY" data-wow-delay="0.2s" src="{{ asset('images/sign-in-up.png') }}" alt="logo"> Sign in/Sign up</a>
              @else
                <a href="{{ route('profile.show') }}" class="btn btn-primary"><img class="wow flipInY" data-wow-delay="0.2s" src="{{ asset('images/sign-in-up.png') }}" alt="logo"> {{ auth()->user()->name }}</a>
              @endguest
              <a href="{{ route('seller.products.create') }}" class="btn btn-primary sell-btn"><img class="wow flipInY" data-wow-delay="0.2s" src="{{ asset('images/sell-icon.svg') }}" alt="logo"> Sell</a>
            </div>
          </div>
        </div>
      </div>
    </header>

    
      
      <div class="content-section">
        @yield('content')
      </div>
    
    
    <footer class="footer-main for-guest-usr">
        <div class="container">
          <div class="ad-system">
            <div class="left-ad">
              <img src="{{ asset('images/university-logo.svg') }}" alt="brand-logo">
            </div>
            <div class="right-ad">
              <ul>
                <li><a href="{{url('/')}}">Home</a></li>
                <li><a href="{{ url('/about') }}">About Us</a></li>
                <li><a href="{{ url('/contact') }}">Contact Us</a></li>
                <li><a href="{{ url('/terms') }}">Terms And Conditions</a></li>
                <li><a href="{{ url('/privacy') }}">Privacy Policy</a></li>
              </ul>
            </div>
          </div>
          <div class="copyright">
            <p>© 2025 Copyright uninest. All Rights Reserved</p>
            <ul class="social-icons">
              <li>
                <a href="#"><img src="{{ asset('images/facebook.svg') }}" alt="google"></a>
              </li>
              <li>
                <a href="#"><img src="{{ asset('images/twitter.svg') }}" alt="google"></a>
              </li>
              <li>
                <a href="https://www.instagram.com/uninest_shop/"><img src="{{ asset('images/instagram.svg') }}" alt="google"></a>
              </li>
              <li>
                <a href="#"><img src="{{ asset('images/linkedin.svg') }}" alt="google"></a>
              </li>
            </ul>
          </div>
        </div>
      </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('js/wow.min.js') }}"></script>
    <script src="{{ asset('js/jquery.slim.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/owl.carousel.js') }}"></script>
    <script src="{{ asset('js/wow.js') }}"></script>
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
    </script>

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
  </body>
</html>

