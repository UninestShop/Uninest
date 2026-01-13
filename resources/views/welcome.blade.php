@extends('layouts.app')
@section('content')
<div class="container">
  <div class="nav-section">
    <nav class="navbar navbar-expand-lg navbar-light">
      <a class="navbar-brand" href="#"></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <img src="images/menu-icon.png" alt="menu">
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <span class="close-btn"><img src="images/close.png" alt="close"></span>
        <ul class="navbar-nav mr-auto wow fadeIn" data-wow-delay="0.2s">
          <li class="nav-item {{ !request('category') ? 'active' : '' }}">
            <a class="nav-link filter-toggle" href="{{ route('products.index') }}">All Items</a>
          </li>
          @foreach($categories as $nav_category)
          <li class="nav-item {{ request('category') == $nav_category->id ? 'active' : '' }}">
            <a class="nav-link filter-toggle" href="{{ route('products.index', ['category' => $nav_category->id]) }}">
              {{ $nav_category->name }}
            </a>
          </li>
          @endforeach
        </ul>
      </div>
    </nav>
  </div>
</div>

@guest
<div class="container mt-3 mb-3">
  <div class="alert alert-info alert-dismissible fade show" role="alert">
    <strong>Welcome!</strong> Browse freely as a guest. <a href="{{ url('/login') }}" class="alert-link">Log in</a> or <a href="{{ url('/register') }}" class="alert-link">sign up</a> to view details and contact sellers.
    {{-- <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button> --}}
  </div>
</div>
@endguest

<div class="main-listing">
  <div class="container">
    <ul class="product-list">
      @foreach($featuredProducts as $product)
      <li 
        onclick="handleProductClick('{{ route('products.show', ['product' => $product->id]) }}');" 
        style="cursor: pointer;" 
        data-product-id="{{ $product->id }}">
        @php
        $photos = $product->photos;
        if (!is_array($photos) && is_string($photos)) {
          $photos = json_decode($photos) ?: [];
        }
        $condition = $product->condition ?? 'Like New';
        @endphp
        <figure>
          @if(is_array($photos) && count($photos) > 0)
          <img src="{{ asset($photos[0]) }}" alt="{{ $product->name }}" onerror="this.onerror=null; this.src='{{ asset('images/no-image.png') }}';">
          @else
          <div class="no-image">No Image</div>
          @endif
        </figure>
        <label>{{ $product->condition }}</label>
        <div class="item-content">
          <span class="product-name">{{ $product->name }}</span>
          <p><img src="{{ asset('images/label-icon.svg') }}" alt="category"> {{ $product->category->name ?? 'Category' }}</p>
          <p><img src="{{ asset('images/location-icon.svg') }}" alt="location"> {{ $product->location ?? 'University Campus' }}</p>
          <p><img src="{{ asset('images/watch-icon.svg') }}" alt="time"> {{ $product->created_at->diffForHumans() }}</p>
          <span class="price-tag">${{ number_format($product->mrp, 2) }}
            @if($product->payment_method)
              @php
                $payment_method = is_string($product->payment_method) ? json_decode($product->payment_method, true) : $product->payment_method;
                $payment_methods = is_array($payment_method) ? $payment_method : ['Cash'];
              @endphp
              @foreach($payment_methods as $method)
                <label class="payment-method-tag">{{ $method }}</label>
              @endforeach
            @else
              <label class="payment-method-tag">Cash</label>
            @endif
          </span>
          @guest
  
          @endguest
        </div>
      </li>
      
      @endforeach
    </ul>
  </div>
</div>

<!-- Bootstrap Modal for Location Access Denied -->
<div class="modal fade" id="locationDeniedModal" tabindex="-1" aria-labelledby="locationDeniedModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="locationDeniedModalLabel">Enable Your Location To Access</h5>
      </div>
      <div class="modal-body" id="locationDeniedModalBody">
        Location access denied. Please enable location services to see nearby products.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="locationDeniedOkBtn">OK</button>
      </div>
    </div>
  </div>
</div>
@endsection

<!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
<script src="{{ asset('js/jquery.slim.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/owl.carousel.js') }}"></script>
<script src="{{ asset('js/wow.js') }}"></script>
<script>
function showLocationDeniedModal(message) {
  var modal = new bootstrap.Modal(document.getElementById('locationDeniedModal'));
  var body = document.getElementById('locationDeniedModalBody');
  if (body && message) body.textContent = message;
  modal.show();
  document.getElementById('locationDeniedOkBtn').onclick = function() {
    modal.hide();
    // Reset the message for next time
    if (body) body.textContent = 'Location access denied. Please enable location services to see nearby products.';
  };
}

window.onload = function() {
  getLocation();
};

function getLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      function(position) {
        const latitude = position.coords.latitude;
        const longitude = position.coords.longitude;
        fetch('/save-location', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          body: JSON.stringify({
            latitude: latitude,
            longitude: longitude
          })
        })
        .then(response => response.json())
        .then(data => {
          window.location.reload();
        })
        .catch(error => {
          console.error('Error saving location:', error);
        });
      },
      function(error) {
        switch(error.code) {
          case error.PERMISSION_DENIED:
            showLocationDeniedModal('Location access is required. Please enable your location services to see nearby products.');
            break;
          case error.POSITION_UNAVAILABLE:
            // showLocationDeniedModal('Unable to retrieve your location.');
            // Skip showing modal for position unavailable
            console.log('Position unavailable');
            break;
          case error.TIMEOUT:
            showLocationDeniedModal('Location request timed out. Please allow your location.');
            break;
          // default:
          //   showLocationDeniedModal('An unknown error occurred while retrieving your location.');
        }
        console.error('Geolocation error:', error.message);
      },
      { timeout: 10000, maximumAge: 60000 }
    );
  } else {
    showLocationDeniedModal('Geolocation is not supported by this browser. Some features may not work properly.');
    console.error('Geolocation is not supported by this browser.');
  }
}
</script>

<script>
  $(document).ready(function() {
    $(".navbar-toggler").on("click", function() {
      $("body").addClass("show");
    });
    $(".close-btn").on("click", function() {
      $(".navbar-collapse").removeClass("show");
    });
    $(".close-btn").on("click", function() {
      $("body").removeClass("show");
    });
  });

  // Function to handle product clicks - direct navigation for all users
  function handleProductClick(url) {
    window.location.href = url;
  }

  // Add category parameters to the current URL when filter links are clicked
  $(document).ready(function() {
    // No modal-specific code here
  });

  new WOW().init();

  $(document).ready(function() {
    $('.logo-slider').owlCarousel({
      loop: true,
      margin: 10,
      responsiveClass: true,
      dots: false,
      autoplay: true,
      responsive: {
        0: {
          items: 1,
          nav: false
        },
        600: {
          items: 2,
          nav: false
        },
        1000: {
          items: 4,
          nav: false,
          loop: false,
          margin: 50
        }
      }
    });
  });
</script>

<style>
  .product-list li {
    position: relative;
  }
  
  .product-list li .item-content {
    padding-bottom: 10px;
  }
  
  @guest
  .product-list li:after {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.02);
    pointer-events: none;
  }
  
  .product-list li .btn {
    position: relative;
    z-index: 5;
    pointer-events: auto;
  }
  @endguest
</style>

