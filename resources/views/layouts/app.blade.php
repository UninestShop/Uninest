<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
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
      
      /* Full-width content when sidebar is hidden */
      .content-section.full-width {
        width: 100%;
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

      /* Profile picture styles for header */
      .header-profile-pic {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 5px;
      }
    </style>
  </head>
  <body>
    
    <header>
      <div class="header-top my-profile-space">
        <div class="container">
          <div class="header-main">
            <div class="logo">
              <a href="{{ url('/') }}"><img class="wow flipInY" data-wow-delay="0.2s" src="{{ asset('images/university-logo.svg') }}" alt="logo"></a>
            </div>
            <div class="search-section">
              <form action="{{ route('products.index') }}" method="GET" id="searchForm">
                {{-- <select class="form-control" name="category" id="categorySelect">
                  <option value="">Search by Category</option>
                  @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                      {{ $category->name }}
                    </option>
                  @endforeach
                </select> --}}
                
                  <div class="cstm-dropdwn">
                    <span id="selectLocationSpan" style="cursor:pointer;">
                    {{-- <span id="locationText" title="" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 160px; display: inline-block;">select location</span> --}}
                    </span>
                    <div class="location-input" id="locationInputWrapper" style="display:block;">
                      <div class="crnt-lctn">
                        <input type="text" class="form-control" name="location" id="inputAddress" ></input>
                        <button type="button" class="current_location_btn" id="currentLocationOnly" ><img src="{{asset('images/map-location-new.png')}}" alt=""></button>
                      </div>
                      <input type="hidden" id="location_lat" name="lat">
                      <input type="hidden" id="location_long" name="long">
                      <div id="map" style="display:none;"></div>
                          {{-- <input type="text" class="form-control" placeholder="Current location" id="currentLocationOnly" readonly style="cursor:pointer; background:#f8f9fa;" title="Click to use your current location"> --}}
                      </div>
                  </div>
                  <div class="srch-inpt">
                    <input type="text" name="search" id="searchInput" class="form-control" placeholder="Search item..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary"><img src="{{asset('images/search-icon.png')}}" alt=""></button>
                  </div>
              </form>
            </div>
            <div class="head-top-right">
              @guest
                <a href="{{ route('login') }}" class="btn btn-primary"><img class="wow flipInY" data-wow-delay="0.2s" src="{{ asset('images/sign-in-up.png') }}" alt="logo"> Sign in/Sign up</a>
              @else
                <a href="{{ route('profile.show') }}" class="btn btn-primary">
                  @if(auth()->user()->profile_picture)
                    <img class="wow flipInY header-profile-pic" data-wow-delay="0.2s" src="{{ asset('storage/' . auth()->user()->profile_picture) }}" alt="profile">
                  @else
                    <img class="wow flipInY" data-wow-delay="0.2s" src="{{ asset('images/sign-in-up.png') }}" alt="logo">
                  @endif
                  {{ auth()->user()->name }}
                </a>
              @endguest
              <a href="{{ route('seller.products.create') }}" class="btn btn-primary sell-btn"><img class="wow flipInY" data-wow-delay="0.2s" src="{{ asset('images/sell-icon.svg') }}" alt="logo"> Sell</a>
            </div>
          </div>
        </div>
      </div>
    </header>

    <div class="main-container">
      @if(!(Request::is('products') || Request::is('products/*') || (Request::is('/') && Auth::check())))
        @include('layouts.sidebar')
      @endif
      
      <div class="content-section @if(Request::is('products') || Request::is('products/*') || (Request::is('/') && Auth::check())) full-width @endif">
        @yield('content')
      </div>
    </div>
    
    <footer class="footer-main">
        <div class="container">
          <div class="ad-system">
            <div class="left-ad">
              <img src="{{ asset('images/university-logo.svg') }}" alt="brand-logo">
            </div>
            <div class="right-ad">
              <ul>
                <li><a href="{{route('/')}}">Home</a></li>
                <li><a href="{{ url('/about') }}">About Us</a></li>
                <li><a href="{{ url('/contact') }}">Contact Us</a></li>
                <li><a href="{{ url('/terms') }}">Terms And Conditions</a></li>
                <li><a href="{{ url('/privacy') }}">Privacy Policy</a></li>
              </ul>
            </div>
          </div>
          <div class="copyright">
            <p>© 2025 Copyright university. All Rights Reserved</p>
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
    <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('js/wow.min.js') }}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.service.google_maps.api_key') }}&libraries=places"></script>

<script>
function initLocationAutocomplete({ inputId, latId, lngId, mapId, currentLocationSelector }) {
  if (typeof google === 'undefined' || !google.maps) return;
  const input = document.getElementById(inputId);
  if (!input) return;
  let map = null;
  let marker = null;
  let mapDiv = document.getElementById(mapId);
  if (!mapDiv) {
    mapDiv = document.createElement('div');
    mapDiv.id = mapId;
    mapDiv.style.display = 'none';
    document.body.appendChild(mapDiv);
  }
  function setLatLng(lat, lng) {
    if (latId && document.getElementById(latId)) document.getElementById(latId).value = lat;
    if (lngId && document.getElementById(lngId)) document.getElementById(lngId).value = lng;
  }
  function setAddress(addr) {
    input.value = addr;
  }
  function showMap(lat, lng) {
    map = new google.maps.Map(mapDiv, {
      center: { lat: parseFloat(lat), lng: parseFloat(lng) },
      zoom: 13,
      mapTypeControl: false,
    });
    marker = new google.maps.Marker({
      icon: 'http://maps.google.com/mapfiles/ms/icons/green-dot.png',
      map,
      anchorPoint: new google.maps.Point(0, -29),
    });
    return map;
  }
  function initAutocomplete(lat, lng) {
    showMap(lat, lng);
    const autocomplete = new google.maps.places.Autocomplete(input, {});
    autocomplete.bindTo('bounds', map);
    autocomplete.addListener('place_changed', function() {
      marker.setVisible(false);
      const place = autocomplete.getPlace();
      if (!place.geometry || !place.geometry.location) {
        window.alert("No details available for input: '" + place.name + "'");
        return;
      }
      setAddress(place.formatted_address || place.name);
      setLatLng(place.geometry.location.lat(), place.geometry.location.lng());
      if (place.geometry.viewport) {
        map.fitBounds(place.geometry.viewport);
      } else {
        map.setCenter(place.geometry.location);
        map.setZoom(17);
      }
      marker.setPosition(place.geometry.location);
      marker.setVisible(true);
    });
  }
  // Geolocation logic
  function getLocationAndInit() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function(position) {
        initAutocomplete(position.coords.latitude, position.coords.longitude);
      }, function() {
        // fallback to default
        initAutocomplete(40.749933, -73.98633);
      });
    } else {
      initAutocomplete(40.749933, -73.98633);
    }
  }
  getLocationAndInit();
  // Handle current location click
  if (currentLocationSelector) {
    const currentLocInput = document.querySelector(currentLocationSelector);
    if (currentLocInput) {
      currentLocInput.addEventListener('click', function(e) {
        e.preventDefault();
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            setLatLng(lat, lng);
            // Reverse geocode to get address
            const geocoder = new google.maps.Geocoder();
            const latlng = { lat: parseFloat(lat), lng: parseFloat(lng) };
            geocoder.geocode({ location: latlng }, function(results, status) {
              if (status === 'OK' && results[0]) {
                setAddress(results[0].formatted_address);
              } else {
                alert('Could not get address for your location.');
              }
            });
          },
          //  function() {
          //   alert('Unable to retrieve your location. Your location access is required.');
          // }
          );
        } else {
          alert('Geolocation is not supported by your browser.');
        }
      });
    }
  }
}
// Initialize for search bar (header)
document.addEventListener('DOMContentLoaded', function() {
  initLocationAutocomplete({
    inputId: 'inputAddress',
    latId: 'location_lat',
    lngId: 'location_long',
    mapId: 'map',
    currentLocationSelector: '.location-input input[placeholder="Current location"]'
  });
});
</script>
 
    <script>
      new WOW().init();
      
      // Ensure search form values persist
      $(document).ready(function() {
        // Check if there are URL parameters
        const urlParams = new URLSearchParams(window.location.search);
        const searchParam = urlParams.get('search');
        const categoryParam = urlParams.get('category');
        
        // Set form values from URL parameters
        if (searchParam) {
          $('#searchInput').val(searchParam);
        }
        
        if (categoryParam) {
          $('#categorySelect').val(categoryParam);
        }
        
      
      });
    </script>
    <script src="{{ asset('js/jquery.slim.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/owl.carousel.js') }}"></script>
    <script src="{{ asset('js/wow.js') }}"></script>

    @stack('scripts')
    
    @auth
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Create a reusable logout form that can be used throughout the application
        if (!document.getElementById('logout-form')) {
            const logoutFormHTML = `
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
              </form>
            `;
            document.body.insertAdjacentHTML('beforeend', logoutFormHTML);
            
            // Intercept all clicks on the page
            document.addEventListener('click', function(e) {
                // Check if the clicked element is a logout link or button
                let target = e.target;
                while (target && !target.matches('a[href*="logout"], button[data-logout="true"], a[data-logout="true"]')) {
                    target = target.parentElement;
                    if (!target) break;
                }
                
                // If it's not a logout element, return
                if (!target) return;
                
                // Prevent default action and submit the form
                e.preventDefault();
                e.stopPropagation();
                
                // Submit the logout form instead
                document.getElementById('logout-form').submit();
            });
        }

        // User rejection check - automatically logout if user is rejected
        @if(auth()->user()->status === 'rejected')
            // Create and show rejection modal if it doesn't exist
            if (!document.getElementById('accountRejectedModal')) {
                const modalHTML = `
                  <div class="modal fade" id="accountRejectedModal" tabindex="-1" aria-labelledby="accountRejectedModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="accountRejectedModalLabel">Account Rejected</h5>
                        </div>
                        <div class="modal-body">
                          <p>Your account has been rejected by the administrator. If you believe this is an error, please contact support.</p>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-primary" id="rejectionOkButton">OK</button>
                        </div>
                      </div>
                    </div>
                  </div>
                `;
                document.body.insertAdjacentHTML('beforeend', modalHTML);
                
                // Show the modal
                const rejectionModal = new bootstrap.Modal(document.getElementById('accountRejectedModal'));
                rejectionModal.show();
                
                // Add event listener to the OK button
                document.getElementById('rejectionOkButton').addEventListener('click', function() {
                    // Submit the logout form
                    document.getElementById('logout-form').submit();
                });
            }
        @endif
        
        // Profile completeness check
        @if(isset($profileComplete) && !$profileComplete)
        // Define the showIncompleteProfileModal function if it doesn't exist
        if (typeof showIncompleteProfileModal !== 'function') {
            // Create the incompleteProfileModal if it doesn't exist
            if (!document.getElementById('incompleteProfileModal')) {
                const modalHTML = `
                  <div class="modal fade" id="incompleteProfileModal" tabindex="-1" aria-labelledby="incompleteProfileModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="incompleteProfileModalLabel">Complete Your Profile</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <p>Please complete your profile information before accessing other features.</p>
                          <ul>
                            @if(isset($incompleteFields))
                              @foreach($incompleteFields as $field)
                                <li>{{ $field }}</li>
                              @endforeach
                            @else
                              <li>Required profile information</li>
                            @endif
                          </ul>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-primary" id="profileModalOkBtn" data-dismiss="modal" onclick="redirectToProfile()">Ok</button>
                        </div>
                      </div>
                    </div>
                  </div>
                `;
                document.body.insertAdjacentHTML('beforeend', modalHTML);
            }

            function showIncompleteProfileModal() {
                // Try different modal showing approaches based on Bootstrap version
                const modalElement = document.getElementById('incompleteProfileModal');
                
                try {
                    // Try Bootstrap 5 approach
                    if (typeof bootstrap !== 'undefined') {
                        const modal = new bootstrap.Modal(modalElement);
                        modal.show();
                    } 
                    // Try Bootstrap 4 approach with jQuery
                    else if (typeof $ !== 'undefined' && $.fn.modal) {
                        $(modalElement).modal('show');
                    } 
                    // Fallback approach
                    else {
                        modalElement.style.display = 'block';
                        modalElement.classList.add('show');
                        document.body.classList.add('modal-open');
                        
                        // Create backdrop if it doesn't exist
                        if (document.getElementsByClassName('modal-backdrop').length === 0) {
                            const backdrop = document.createElement('div');
                            backdrop.className = 'modal-backdrop fade show';
                            document.body.appendChild(backdrop);
                        }
                    }
                } catch (error) {
                    console.error('Modal error:', error);
                    // Fallback to direct manipulation as last resort
                    modalElement.style.display = 'block';
                    modalElement.classList.add('show');
                }
            }
            
            // Function to redirect to profile page
            function redirectToProfile() {
                window.location.href = '{{ route("profile.show") }}';
            }

            // Register global function
            window.showIncompleteProfileModal = showIncompleteProfileModal;
            window.redirectToProfile = redirectToProfile;
        }

        // Intercept search form submissions
        const searchForm = document.getElementById('searchForm');
        if (searchForm) {
            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                showIncompleteProfileModal();
                return false;
            });
        }

        // Intercept category select changes
        const categorySelect = document.getElementById('categorySelect');
        if (categorySelect) {
            categorySelect.addEventListener('change', function(e) {
                e.preventDefault();
                showIncompleteProfileModal();
                // Reset selection to default
                setTimeout(() => {
                    this.selectedIndex = 0;
                }, 100);
                return false;
            });
            
            // Also disable the select to provide visual feedback
            categorySelect.classList.add('incomplete-profile-disabled');
        }

        // Intercept search input interactions
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            // Prevent input by showing modal when focused
            searchInput.addEventListener('focus', function(e) {
                e.preventDefault();
                this.blur(); // Remove focus
                showIncompleteProfileModal();
                return false;
            });
            
            // Add visual indication
            searchInput.classList.add('incomplete-profile-disabled');
        }
        
        // Add some CSS to indicate disabled state
        const style = document.createElement('style');
        style.textContent = `
            .incomplete-profile-disabled {
                background-color: #f8f9fa;
                cursor: not-allowed;
            }
        `;
        document.head.appendChild(style);
        @endif
    });
    </script>
    @endauth

    <script>
      $(document).ready(function() {
        $('#currentLocationOnly').on('click', function(e) {
          e.preventDefault();
          if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
              const lat = position.coords.latitude;
              const lng = position.coords.longitude;
              $('#location_lat').val(lat);
              $('#location_long').val(lng);
              if (typeof google !== 'undefined' && google.maps) {
                const geocoder = new google.maps.Geocoder();
                const latlng = { lat: parseFloat(lat), lng: parseFloat(lng) };
                geocoder.geocode({ location: latlng }, function(results, status) {
                  if (status === 'OK' && results[0]) {
                    $('#inputAddress').val(results[0].formatted_address);
                  } else {
                    $('#inputAddress').val('Current Location');
                  }
                  $('#searchForm').submit();
                });
              } else {
                $('#inputAddress').val('Current Location');
                $('#searchForm').submit();
              }
            }, 
            // function() {
            //   alert('Unable to retrieve your location. Your location access is required.');
            // }
          );
          } else {
            alert('Geolocation is not supported by your browser.');
          }
        });
        
        $('#selectLocationSpan').on('click', function() {
          $('#locationInputWrapper').css('display', 'block');
          $('#inputAddress').focus();
        });
      });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
          const urlParams = new URLSearchParams(window.location.search);
          if (urlParams.has('location') || urlParams.has('lat') || urlParams.has('long')) {
          if (urlParams.has('location')) {
            const locationInput = document.getElementById('inputAddress');
            if (locationInput) {
            locationInput.value = decodeURIComponent(urlParams.get('location'));
            const wrapper = document.getElementById('locationInputWrapper');
            if (wrapper) wrapper.style.display = 'block';
            }
          }
          
          if (urlParams.has('lat')) {
            const latInput = document.getElementById('location_lat');
            if (latInput) latInput.value = urlParams.get('lat');
          }
          
          if (urlParams.has('long')) {
            const longInput = document.getElementById('location_long');
            if (longInput) longInput.value = urlParams.get('long');
          }
          }
        });
       </script>
       <script>
      // --- Robust location/lat/long handling for search form ---
      document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('searchForm');
        const input = document.getElementById('inputAddress');
        const latInput = document.getElementById('location_lat');
        const lngInput = document.getElementById('location_long');
        let submitting = false;

        if (!form || !input || !latInput || !lngInput) return;

        // Helper: check if lat/lng are set
        function latLngSet() {
          return latInput.value && lngInput.value;
        }

        // Helper: geocode address and set lat/lng
        function geocodeAddress(address, callback) {
          if (typeof google === 'undefined' || !google.maps) {
            callback(false);
            return;
          }
          const geocoder = new google.maps.Geocoder();
          geocoder.geocode({ address: address }, function(results, status) {
            if (status === 'OK' && results[0] && results[0].geometry && results[0].geometry.location) {
              latInput.value = results[0].geometry.location.lat();
              lngInput.value = results[0].geometry.location.lng();
              callback(true);
            } else {
              callback(false);
            }
          });
        }

        // On form submit, ensure lat/lng are set
        form.addEventListener('submit', function(e) {
          if (submitting) return; // Prevent double submit
          if (input.value && !latLngSet()) {
            e.preventDefault();
            geocodeAddress(input.value, function(success) {
              if (success) {
                submitting = true;
                form.submit();
              } else {
                alert('Could not determine location. Please select a valid address.');
              }
            });
          }
          // else: allow submit
        });

        // On autocomplete place selection, always set lat/lng
        if (typeof google !== 'undefined' && google.maps && google.maps.places) {
          const autocomplete = new google.maps.places.Autocomplete(input, {});
          autocomplete.addListener('place_changed', function() {
            const place = autocomplete.getPlace();
            if (place.geometry && place.geometry.location) {
              latInput.value = place.geometry.location.lat();
              lngInput.value = place.geometry.location.lng();
            }
          });
        }

        // On Enter key in input, trigger form submit (handled above)
        input.addEventListener('keydown', function(e) {
          if (e.key === 'Enter') {
            // Let form submit handler handle geocoding
            setTimeout(function() {
              if (!latLngSet()) {
                // Will be handled by submit event
              }
            }, 10);
          }
        });

        // Current location button: set lat/lng and submit
        const currentLocBtn = document.getElementById('currentLocationOnly');
        if (currentLocBtn) {
          currentLocBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (navigator.geolocation) {
              navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                latInput.value = lat;
                lngInput.value = lng;
                if (typeof google !== 'undefined' && google.maps) {
                  const geocoder = new google.maps.Geocoder();
                  const latlng = { lat: parseFloat(lat), lng: parseFloat(lng) };
                  geocoder.geocode({ location: latlng }, function(results, status) {
                    if (status === 'OK' && results[0]) {
                      input.value = results[0].formatted_address;
                    } else {
                      input.value = 'Current Location';
                    }
                    submitting = true;
                    form.submit();
                  });
                } else {
                  input.value = 'Current Location';
                  submitting = true;
                  form.submit();
                }
              }, function() {
                alert('Please first enable your location. Your location access is required.');
              });
            } else {
              alert('Geolocation is not supported by your browser.');
            }
          });
        }
      });
    </script>

    <script>
      document.addEventListener('DOMContentLoaded', function() {
      // Remove .my-profile-space from header-top only on home or products main page (not product/id)
      const isHome = window.location.pathname === '/';
      const isProductsMain = window.location.pathname === '/products';
      const isProductShow = /^\/products\/\d+$/.test(window.location.pathname);

      const headerTop = document.querySelector('.header-top');
      if ((isHome || isProductsMain) && headerTop && headerTop.classList.contains('my-profile-space')) {
        headerTop.classList.remove('my-profile-space');
      }
      // Add class for products.show route
      if (isProductShow && headerTop && !headerTop.classList.contains('product-show-page')) {
        headerTop.classList.add('product-show-page');
      }
      });

       // Add real-time message checking
      function updateMessageCount() {
        fetch('/check-messages')
          .then(response => response.json())
          .then(data => {
            // Always ensure the badge exists in header
            let messageBadges = document.querySelectorAll('.badge.bg-danger');
            if (messageBadges.length === 0) {
              // If not found, create it
              const headerBadge = document.createElement('span');
              headerBadge.className = 'badge bg-danger';
              headerBadge.style.display = 'none';
              document.querySelector('.header-messages')?.appendChild(headerBadge);
              messageBadges = document.querySelectorAll('.badge.bg-danger');
            }

            // Update header badges
            messageBadges.forEach(badge => {
              if (data.unreadCount > 0) {
                badge.textContent = data.unreadCount + ' Unread';
                badge.style.display = 'inline';
                badge.style.color = 'white';
              } else {
                badge.style.display = 'none';
              }
            });

            // Sidebar badge
            let sidebarBadge = document.querySelector('.pro-linkss .badge.bg-danger');
            if (!sidebarBadge) {
              sidebarBadge = document.createElement('span');
              sidebarBadge.className = 'badge bg-danger';
              sidebarBadge.style.display = 'none';
              document.querySelector('.pro-linkss')?.appendChild(sidebarBadge);
            }

            if (data.unreadCount > 0) {
              sidebarBadge.textContent = data.unreadCount;
              sidebarBadge.style.display = 'inline';
            } else {
              sidebarBadge.style.display = 'none';
            }
          })
          .catch(error => console.error('Error checking messages:', error));
      }

      // Run immediately so first count shows
      updateMessageCount();

      // Then every 10 seconds
      setInterval(updateMessageCount, 10000);

    
    </script>
</body>
</html>

