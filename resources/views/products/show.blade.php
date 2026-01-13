@extends('layouts.app')

@section('content')
<!-- Flash Messages -->
@if(session('success'))
<div class="container mt-3">
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
    </div>
</div>
@endif

@if(session('error'))
<div class="container mt-3">
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
    </div>
</div>
@endif
<!-- End Flash Messages -->

<div class="product-details">
    <div class="container">
        <div class="product-info">
            <div class="product-left">
              @if($product->photos)
              @php
            $photos = $product->photos;
            if (is_string($photos)) {
                $photos = json_decode($photos, true); // Convert to array explicitly
            }
            $photoSrc = (is_array($photos) && !empty($photos)) ? $photos[0] : asset('images/item-img5.png');
          @endphp
                    

                    <div id="productImageCarousel" class="carousel slide" data-bs-interval="false">
                        <div class="carousel-inner">
                            @foreach($photos as $index => $photo)
                                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                    <figure>
                                        <img src="{{ asset($photo) }}" class="d-block w-100" alt="{{ $product->name }}">
                                    </figure>
                                </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#productImageCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#productImageCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                        @if(is_array($photos) && count($photos) > 1)
                        <div class="carousel-indicators">
                            @foreach($photos as $index => $photo)
                                <button type="button" data-bs-target="#productImageCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}" aria-current="{{ $index == 0 ? 'true' : 'false' }}" aria-label="Slide {{ $index + 1 }}"></button>
                            @endforeach
                        </div>
                    @endif
                    </div>
                @endif
            </div>
            <div class="product-right">
                <h2>{{ $product->name }}</h2>
                <span>{{'$'}} {{$product->mrp}}  <label>{{$product->condition}}</label></span>
                <div class="description mb-4">
                  <h4>Payment Method</h4>
                  @php
                  $paymentMethods = $product->payment_method;
                  if (!is_array($paymentMethods)) {
                    $paymentMethods = json_decode($paymentMethods, true) ?: [$product->payment_method];
                  }
                  @endphp
                  <p>
                  @if(is_array($paymentMethods) && count($paymentMethods) > 0)
                    {{ implode(', ', $paymentMethods) }}
                  @else
                    {{ $product->payment_method }}
                  @endif
                  </p>
                </div>
                <div class="description mb-4">
                    <h4>Description</h4>
                    <p>{{ $product->description }}</p>
                </div>
                <div class="description">
                    <h4><img src="{{asset('images/location-icon.svg')}}" alt="google">{{$product->location ?? 'University Campus'}}</h4>
                    {{-- <p>North Campus - University of Technology</p> --}}
                  </div>
                  <div class="description">
                    <h4><img src="{{asset('images/watch-icon.svg')}}" alt="google"> Posted</h4>
                    <p>{{ $product->created_at->diffForHumans() }}</p>
                  </div>
                  <div class="description">
                    <h4>Seller Information</h4>
                    <div class="profile-img">
                    <figure>
                      <img src="{{ $product->user->profile_picture ? asset('storage/' . $product->user->profile_picture) : asset('images/profile-img.png') }}" alt="User Profile">
                    </figure>
                      <p>
                        <i class="fas fa-user"></i> {{ $product->user->name }}<br>
                        <i class="fas fa-university"></i> {{ $product->user->university_name }}
                      </p>
                    </div>
                  </div>
                  <div class="prdct-btn">
                    @auth
                    <form action="{{ route('messages.start', $product) }}" method="POST">
                        @csrf
                        @if(auth()->id() != $product->user_id)
                        <button type="submit" class="btn btn-primary chat-btn">
                            <i class="fas fa-comments"></i> Chat With Seller
                        </button>
                        @endif
                        @if($product->user_id !== auth()->id())
                            {{-- <form action="{{ route('transactions.initiate', $product) }}" method="POST"> --}}
                                {{-- @csrf --}}
                                {{-- <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-handshake"></i> Buy Now
                                </button> --}}
                            {{-- </form> --}}
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary chat-btn">
                            <i class="fas fa-comments"></i> Login to Chat With Seller
                        </a>
                    @endauth
                    
                    @if(auth()->check() && auth()->id() != $product->user_id)
                    <a href="#" class="chat-btn report-btn" data-bs-toggle="modal" data-bs-target="#reportModal">
                        <img src="{{asset('images/report-icon.svg')}}" alt="google">Report
                    </a>
                    @elseif(!auth()->check())
                    <a href="{{ route('login') }}" class="chat-btn report-btn">
                        <img src="{{asset('images/report-icon.svg')}}" alt="google">Login to Report
                    </a>
                    @endif
                @if(auth()->check())
                </form>
                @endif                    
                </div>
            </div>
        </div>
    </div>
</div>

@guest
<div class="modal fade" id="loginSignupModal" tabindex="-1" role="dialog" aria-labelledby="loginSignupModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center w-100" id="loginSignupModalLabel">Authentication Required</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
        <p>Please login or signup to view product details</p>
        <div class="mt-4">
          <a href="{{ url('/register') }}" class="btn btn-primary me-2">Sign Up</a>
          <a href="{{ url('/login') }}" class="btn btn-outline-primary">Login</a>
        </div>
      </div>
    </div>
  </div>
</div>
@endguest

<!-- Report Modal -->
<div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="reportModalLabel">Report this listing</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="reportForm" action="{{ route('products.report', $product) }}" method="POST">
          @csrf
            <div class="mb-3">
            <label class="form-label">Select a reason: <span class="text-danger">*</span></label>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="report_reason" id="reason1" value="duplicate_listing" required>
              <label class="form-check-label" for="reason1">
              Duplicate listing
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="report_reason" id="reason2" value="irrelevant_product">
              <label class="form-check-label" for="reason2">
              Irrelevant product
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="report_reason" id="reason3" value="suspicious_seller">
              <label class="form-check-label" for="reason3">
              Suspicious Seller profile
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="report_reason" id="reason4" value="inappropriate_listing">
              <label class="form-check-label" for="reason4">
              Inappropriate listing
              </label>
            </div>
            <div class="text-danger mt-2 fw-bold" id="reason-error" style="display: none;">
              Please select a reason for reporting this listing.
            </div>
            @error('report_reason')
              <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
            </div>
          <div class="mb-3">
            <label for="reportDetails" class="form-label">Additional details (optional)</label>
            <textarea class="form-control" id="reportDetails" name="report_details" rows="3"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" onclick="validateAndSubmitReport()">Report</button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var carousel = document.getElementById('productImageCarousel');
    if (carousel) {
        var carouselInstance = new bootstrap.Carousel(carousel, {
            interval: false,
            wrap: true
        });
    }
});

function validateAndSubmitReport() {
    // Check if any reason radio button is selected
    const radioButtons = document.querySelectorAll('input[name="report_reason"]');
    let selected = false;
    
    radioButtons.forEach(radio => {
        if (radio.checked) {
            selected = true;
        }
    });
    
    const errorElement = document.getElementById('reason-error');
    
    if (!selected) {
        // Show error message
        errorElement.style.display = 'block';
        // Shake the error message to draw attention
        errorElement.classList.add('shake');
        // Remove shake class after animation completes
        setTimeout(() => {
            errorElement.classList.remove('shake');
        }, 500);
        // Scroll to error message
        errorElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return false;
    } else {
        // Hide error message
        errorElement.style.display = 'none';
        // Submit the form
        document.getElementById('reportForm').submit();
    }
}

// Add CSS for shake animation
document.addEventListener('DOMContentLoaded', function() {
    // Add CSS for shake animation
    const style = document.createElement('style');
    style.textContent = `
        .shake {
            animation: shake 0.5s;
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
    `;
    document.head.appendChild(style);
    
    // Carousel code (keeping existing code)
    var carousel = document.getElementById('productImageCarousel');
    if (carousel) {
        var carouselInstance = new bootstrap.Carousel(carousel, {
            interval: false,
            wrap: true
        });
    }
});
</script>

@guest
<script>
  // Prevent modal from closing when clicking outside
  var showLoginModal = function(event) {
    if(event) event.preventDefault();
    $('#loginSignupModal').modal({
      backdrop: 'static',  // Prevents closing when clicking outside
      keyboard: false      // Prevents closing with ESC key
    });
    $('#loginSignupModal').modal('show');
    return false;
  };

  // Wait until document is ready and scripts are loaded
  document.addEventListener('DOMContentLoaded', function() {
    // Ensure jQuery and Bootstrap are loaded before attempting to show modal
    if (typeof $ !== 'undefined' && typeof $.fn.modal !== 'undefined') {
      // Use setTimeout for reliability
      setTimeout(function() {
        // Configure modal to prevent closing on outside click
        $('#loginSignupModal').modal({
          backdrop: 'static',
          keyboard: false
        });
        $('#loginSignupModal').modal('show');
      }, 100); // 2 seconds delay
    }
  });
</script>
@endguest


@endsection
