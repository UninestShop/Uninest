@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Category Header -->
    <div class="card mb-4">
        <div class="card-body">
            <h2>{{ $category->name }}</h2>
            @if($category->description)
                <p class="text-muted mb-0">{{ $category->description }}</p>
            @endif
        </div>
    </div>

    <!-- Products Grid -->
    <div class="row g-4">
        @forelse($products as $product)
            <div class="col-md-3">
                <div class="card h-100">
                    @php
                        // Handle the photos properly without double decoding
                        $photos = $product->photos;
                        // Ensure photos is an array or convert it to one if it's a JSON string
                        if (is_string($photos)) {
                            $photos = json_decode($photos) ?: [];
                        }
                        // If still not an array or null, make it an empty array
                        if (!is_array($photos) && !($photos instanceof Countable)) {
                            $photos = [];
                        }
                    @endphp

                    <div class="position-relative">
                        @if(count($photos) > 0)
                            <img src="{{ asset($photos[0]) }}" class="card-img-top product-img" alt="{{ $product->name }}">
                        @else
                            <div class="no-image-placeholder">No Image</div>
                        @endif

                        <!-- Condition Badge - now on the right side -->
                        <span class="badge bg-info position-absolute top-0 end-0 m-2">
                            {{ $product->condition }}
                        </span>

                        @if($product->is_reserved)
                            <!-- Reserved badge - move to bottom right since condition is now top right -->
                            <span class="badge bg-warning position-absolute bottom-0 end-0 m-2">Reserved</span>
                        @endif
                    </div>

                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="card-title mb-0">{{ $product->name }}</h6>
                            <span class="price font-weight-bold text-primary">${{ number_format($product->selling_price, 2) }}</span>
                        </div>
                        
                        <!-- Fix category display -->
                        <div class="text-muted small mb-2">
                            <i class="fas fa-tag mr-1"></i> {{ $product->category->name }}
                        </div>
                        
                        <!-- Improved location information display with area names -->
                        @if($product->meeting_location)
                            <div class="text-muted small mb-2">
                                <i class="fas fa-map-marker-alt mr-1"></i> 
                                @php
                                    $location = is_array($product->meeting_location) ? $product->meeting_location : json_decode($product->meeting_location, true);
                                @endphp
                                
                                @if(isset($location['address']) && $location['address'])
                                    {{ $location['address'] }}
                                @elseif(isset($location['lat']) && isset($location['lng']))
                                    <span class="location-container" 
                                          data-lat="{{ $location['lat'] }}" 
                                          data-lng="{{ $location['lng'] }}"
                                          id="location-{{ $product->id }}">
                                        <span class="coordinates-display">
                                            {{ number_format($location['lat'], 6) }}, {{ number_format($location['lng'], 6) }}
                                        </span>
                                        <span class="area-name-display d-none"></span>
                                        <a href="https://maps.google.com/?q={{ $location['lat'] }},{{ $location['lng'] }}" 
                                           target="_blank" class="small ml-1">
                                            <i class="fas fa-external-link-alt"></i> Map
                                        </a>
                                    </span>
                                @else
                                    Location available
                                @endif
                            </div>
                        @endif
                        
                        <small>
                            <i class="fas fa-clock mr-1"></i> Listed {{ $product->created_at->diffForHumans() }}
                        </small>
                        
                        <!-- Payment Methods Section -->
                        <div class="mt-2 payment-methods">
                            {{-- <small class="d-block mb-1"><i class="fas fa-money-bill mr-1"></i> Payment Options:</small> --}}
                            <div class="d-flex flex-wrap">
                                <span class="badge bg-light text-dark mr-1 mb-1">
                                    <i class="fas fa-money-bill-wave text-success"></i> Cash
                                </span>
                                <span class="badge bg-light text-dark mr-1 mb-1">
                                    <i class="fab fa-paypal text-primary"></i> PayPal
                                </span>
                                <span class="badge bg-light text-dark mr-1 mb-1">
                                    <i class="fas fa-exchange-alt text-info"></i> Zelle
                                </span>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="badge bg-{{ $product->condition_color }}">{{ $product->condition }}</span>
                            <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-primary">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    No products found in this category.
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $products->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Function to reverse geocode coordinates and get location names
        function getLocationNames() {
            const locationElements = document.querySelectorAll('.location-container');
            
            locationElements.forEach(element => {
                const lat = element.getAttribute('data-lat');
                const lng = element.getAttribute('data-lng');
                const coordsDisplay = element.querySelector('.coordinates-display');
                const areaDisplay = element.querySelector('.area-name-display');
                
                // Use browser's built-in Geocoding API if available
                if (navigator.geolocation && "Geocoder" in window) {
                    const geocoder = new google.maps.Geocoder();
                    const latlng = { lat: parseFloat(lat), lng: parseFloat(lng) };
                    
                    geocoder.geocode({ location: latlng }, (results, status) => {
                        if (status === "OK" && results[0]) {
                            // Find locality or administrative area
                            let areaName = '';
                            for (let i = 0; i < results.length; i++) {
                                const result = results[i];
                                if (result.types.includes('sublocality') || 
                                    result.types.includes('locality') || 
                                    result.types.includes('neighborhood')) {
                                    areaName = result.formatted_address;
                                    break;
                                }
                            }
                            
                            // If found, display area name
                            if (areaName) {
                                areaDisplay.textContent = areaName;
                                areaDisplay.classList.remove('d-none');
                                coordsDisplay.classList.add('d-none');
                            }
                        }
                    });
                } else {
                    // Alternative: Use a free geocoding service API
                    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=15&addressdetails=1`)
                        .then(response => response.json())
                        .then(data => {
                            if (data && data.display_name) {
                                // Extract just the area name, not the full address
                                const parts = data.display_name.split(',');
                                let areaName = parts.slice(0, 3).join(', ');
                                
                                areaDisplay.textContent = areaName;
                                areaDisplay.classList.remove('d-none');
                                coordsDisplay.classList.add('d-none');
                            }
                        })
                        .catch(error => {
                            console.error("Error fetching location name:", error);
                        });
                }
            });
        }
        
        // Call the function
        getLocationNames();
    });
</script>
@endpush
