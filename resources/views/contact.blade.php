@extends('layouts.app')

@section('content')
<div class="container py-5">
  <div class="row">
    <div class="col-12">
      <h1 class="mb-4 cms-heading">Contact Us</h1>
      
      @if(session('success'))
        <div class="alert alert-success">
          {{ session('success') }}
        </div>
      @endif
      
      <div class="row">
        <div class="col-md-6">
          <div class="card contact-form mb-4">
            <div class="card-body">
              <h3>Get in Touch</h3>
              <form action="{{ route('contact.submit') }}" method="POST">
                @csrf
                <div class="form-group">
                  <label for="name">Name</label><span style="color:red">*</span>
                  <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Your Name" value="{{ old('name', Auth::check() ? Auth::user()->name : '') }}" {{ Auth::check() ? 'readonly' : '' }}>
                  @error('name')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="email">Email</label><span style="color:red">*</span>
                  <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Your Email" value="{{ old('email', Auth::check() ? Auth::user()->email : '') }}"{{ Auth::check() ? 'readonly' : '' }}>
                  @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="subject">Subject</label><span style="color:red">*</span>
                  <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" placeholder="Subject" value="{{ old('subject') }}">
                  @error('subject')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="message">Message</label><span style="color:red">*</span>
                  <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="5" placeholder="Your Message">{{ old('message') }}</textarea>
                  @error('message')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
                <button type="submit" class="btn btn-primary">Send Message</button>
              </form>
            </div>
          </div>
        </div>
        
        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
              <h3>Contact Information</h3>
              <p class="mt-4">
                <i class="fas fa-map-marker-alt mr-2"></i>Email : support@uninest.shop
              </p>
              <h3 class="mt-4">Office Hours</h3>
              <p>Monday - Friday: 9:00 AM - 6:00 PM</p>
              {{-- <p>Saturday: 10:00 AM - 2:00 PM</p> --}}
              <p> Saturday & Sunday : Closed</p>
              
              {{-- <h3 class="mt-4">Follow Us</h3>
              <div class="social-links">
                <a href="#" class="btn btn-outline-primary mr-2"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="btn btn-outline-info mr-2"><i class="fab fa-twitter"></i></a>
                <a href="#" class="btn btn-outline-danger mr-2"><i class="fab fa-instagram"></i></a>
                <a href="#" class="btn btn-outline-primary"><i class="fab fa-linkedin-in"></i></a>
              </div> --}}
            </div>
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
</div>
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