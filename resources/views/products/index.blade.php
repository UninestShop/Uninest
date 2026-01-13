@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="nav-section">
          <nav class="navbar navbar-expand-md navbar-light"> <a class="navbar-brand" href="#"></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"> <img src="{{asset('images/menu-icon.png')}}" alt="menu"> </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent"> 
              <span class="close-btn"><img src="{{asset('images/close.png')}}" alt="close"></span>
              <ul class="navbar-nav mr-auto wow fadeIn" data-wow-delay="0.2s">
              <li class="nav-item {{ !request('category') ? 'active' : '' }}">
                <a class="nav-link nav-ajax-link" href="{{ route('products.index') }}" data-url="{{ route('products.index') }}" onclick="event.preventDefault(); handleCategoryClick(this); return false;">All Items</a>
              </li>
              @foreach($categories as $nav_category)
              <li class="nav-item {{ request('category') == $nav_category->id ? 'active' : '' }}">
                {{-- <a class="nav-link nav-ajax-link" href="{{ route('products.index', ['category' => $nav_category->id]) }}" data-url="{{ route('products.index', ['category' => $nav_category->id]) }}" onclick="event.preventDefault(); handleCategoryClick(this); return false;"> --}}
                <a class="nav-link " href="{{ route('products.index', ['category' => $nav_category->id]) }}">
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
      
    <div class="category-filters" id="filterSection" style="display: block;">
      <div class="container">
        <div class="filter-section">
          <h2>{{ request('category') ? $categories->find(request('category'))->name : 'All Products' }}</h2>
          <ul class="dropdown-filter">
            {{-- <li>
              <select class="ajax-filter" data-filter="category">
                <option value="">Select Category</option>
                @foreach($categories as $filter_category)
                <option value="{{ $filter_category->id }}"
                    {{ request('category') == $filter_category->id ? 'selected' : '' }}>
                    {{ $filter_category->name }}
                </option>
                @endforeach
              </select>
            </li> --}}
            <li>
            <div class="sort-by">
            <label>Sort by</label>
            <select class="ajax-filter" data-filter="sort">
              <option value="newest" {{ request('sort') == 'newest' || !request('sort') ? 'selected' : '' }}>Newest</option>
              <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
              <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
            </select>
          </div>
          </li>
            <li>
              <select class="ajax-filter" data-filter="price">
                <option value="">Price Range</option>
                <option value="0-50" {{ request('price') == '0-50' ? 'selected' : '' }}>$0 - $50</option>
                <option value="51-100" {{ request('price') == '51-100' ? 'selected' : '' }}>$51 - $100</option>
                <option value="101-200" {{ request('price') == '101-200' ? 'selected' : '' }}>$101 - $200</option>
                <option value="201-500" {{ request('price') == '201-500' ? 'selected' : '' }}>$201 - $500</option>
                <option value="501-9999" {{ request('price') == '501-9999' ? 'selected' : '' }}>$501+</option>
              </select>
            </li>
            <li>
              <select class="ajax-filter" data-filter="condition" class="{{ request('condition') ? 'active-filter' : '' }}">
                <option value="">Condition {{ request('condition') ? '(' . request('condition') . ')' : '' }}</option>
                <option value="New" {{ request('condition') == 'New' ? 'selected' : '' }}>New</option>
                <option value="Like New" {{ request('condition') == 'Like New' ? 'selected' : '' }}>Like New</option>
                <option value="Good" {{ request('condition') == 'Good' ? 'selected' : '' }}>Good</option>
                <option value="Fair" {{ request('condition') == 'Fair' ? 'selected' : '' }}>Fair</option>
              </select>
              @if(request('condition'))
                <a href="javascript:void(0)" class="clear-filter" data-filter="condition" title="Clear condition filter"></a>
              @endif
            </li>
          </ul>
          
        </div>
      </div>  
    </div>

    @if(request('condition'))
    <div class="active-filters-section container">
      <div class="active-filters">
        {{-- <span>Active filters:</span> --}}
        <div class="filter-badge">
          {{-- Condition: {{ request('condition') }} --}}
          {{-- <a href="{{ request()->fullUrlWithQuery(['condition' => null]) }}" class="remove-filter">×</a> --}}
        </div>
      </div>
    </div>
    @endif

    @php
      $activeFilters = false;
      $hasCategory = request('category');
      $hasPrice = request('price');
      $hasCondition = request('condition');
      $hasSearch = request('search');
      
      // Check if any filters are active
      $activeFilters = $hasCategory || $hasPrice || $hasCondition || $hasSearch;
    @endphp

    <div class="main-listing">
      <div class="container">
        {{-- <div class="filter-toggle-container text-right mb-3">
          <button id="filterToggleBtn" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-filter"></i> Hide Filters
          </button>
        </div> --}}
        
        <ul class="product-list" id="product-list-container">
          @forelse($products as $product)
          <li onclick="window.location='{{ route('products.show', $product->id) }}';" style="cursor: pointer;">
            <figure>
              @php
              $photos = $product->photos;
              if (is_string($photos)) {
                  $photos = json_decode($photos, true); // Convert to array explicitly
              }
              $photoSrc = (is_array($photos) && !empty($photos)) ? $photos[0] : asset('images/item-img5.png');
            @endphp
              <img src="{{ $photoSrc }}" alt="{{ $product->name }}">
            </figure>
            <label>{{ $product->condition }}</label>
            <div class="item-content">
              <a href="{{ route('products.show', $product->id) }}">{{ $product->name ?? '' }}</a>
              <p><img src="images/label-icon.svg" alt="category"> {{ $product->category->name ?? ''}}</p>
              <p><img src="images/location-icon.svg" alt="location"> {{ $product->location ?? 'University Campus' }}</p>
              <p><img src="images/watch-icon.svg" alt="time"> {{ $product->created_at->diffForHumans() }}</p>
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
            </div>
          </li>
          @empty
          <div class="col-12">
            <div class="alert alert-info">
              No products found. Try adjusting your search criteria.
            </div>
          </div>
          @endforelse
        </ul>
        
        <div id="pagination-container">
          {{ $products->links('pagination::bootstrap-4') }}
        </div>
        
        <div class="safety-tips">
          <h2>Safety Tips</h2>
          <ul class="tips-list">
            <li>Always meet in a public place on campus.</li>
            <li>Inspect the item before making payment.</li>
            <li>Never share personal financial information.</li>
            <li>Consider bringing a friend for higher-value transactions.</li>
            <li>Trust your instincts - if something feels wrong, walk away.</li>
          </ul>
          <figure class="tips-label"><img src="images/safety-tips-img.png" alt="google"></figure>
        </div>
      </div>
    </div>

    @push('styles')
    <style>
      .active-filter {
        border-color: #007bff;
      }
      
      .clear-filter {
        position: absolute;
        right: 25px;
        top: 10px;
        color: #dc3545;
        font-weight: bold;
        font-size: 18px;
        text-decoration: none;
      }
      
      .active-filters-section {
        margin: 10px 0;
        padding: 10px 0;
      }
      
      .active-filters {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
      }
      
      .filter-badge {
        display: inline-block;
        background: #f0f0f0;
        padding: 5px 10px;
        border-radius: 20px;
        margin-left: 10px;
        font-size: 0.9em;
      }
      
      .remove-filter {
        margin-left: 5px;
        color: #dc3545;
        text-decoration: none;
        font-weight: bold;
      }
      
      li {
        position: relative;
      }
      
      /* Loading spinner styles */
      .spinner-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.7);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        display: none;
      }
      
      .spinner {
        width: 50px;
        height: 50px;
        border: 5px solid #f3f3f3;
        border-top: 5px solid #3498db;
        border-radius: 50%;
        animation: spin 1s linear infinite;
      }
      
      @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
      }
    </style>
    @endpush

    <!-- Add loading spinner -->
    <div class="spinner-overlay" id="loading-spinner">
      <div class="spinner"></div>
    </div>

    <!-- Add JavaScript for toggling filters -->
    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
      // Track if we're currently handling a navigation update to prevent loops
      let isUpdatingNavigation = false;
      
      $(document).ready(function() {
        const filterSection = document.getElementById('filterSection');
        const filterToggleBtn = document.getElementById('filterToggleBtn');
        
        // Function to toggle filter visibility
        function toggleFilters() {
          if (filterSection.style.display === 'none') {
            filterSection.style.display = 'block';
            filterToggleBtn.innerHTML = '<i class="fas fa-filter"></i> Hide Filters';
          } else {
            filterSection.style.display = 'none';
            filterToggleBtn.innerHTML = '<i class="fas fa-filter"></i> Show Filters';
          }
          // Store current state
          sessionStorage.setItem('filterDisplay', filterSection.style.display);
        }
        
        // Add click event to the toggle button
        if (filterToggleBtn) {
          filterToggleBtn.addEventListener('click', toggleFilters);
        }
        
        // Check for active category specifically
        const hasCategory = {{ request('category') ? 'true' : 'false' }};
        const hasActiveFilters = {{ $activeFilters ? 'true' : 'false' }};
        const isAllItemsPage = {{ !request('category') ? 'true' : 'false' }};
        
        // Get stored preference
        const storedFilterDisplay = sessionStorage.getItem('filterDisplay');
        
        // Always show filters when a category is selected
        if (hasCategory || hasActiveFilters) {
          filterSection.style.display = 'block';
          if (filterToggleBtn) {
            filterToggleBtn.innerHTML = '<i class="fas fa-filter"></i> Hide Filters';
          }
        } else if (isAllItemsPage && storedFilterDisplay === 'block') {
          // For All Items page, respect the stored preference
          filterSection.style.display = 'block';
          if (filterToggleBtn) {
            filterToggleBtn.innerHTML = '<i class="fas fa-filter"></i> Hide Filters';
          }
        }
        
        // Show filter section by default if condition filter is active
        if ({{ request('condition') ? 'true' : 'false' }}) {
          filterSection.style.display = 'block';
          if (filterToggleBtn) {
            filterToggleBtn.innerHTML = '<i class="fas fa-filter"></i> Hide Filters';
          }
        }
        
        // AJAX Filtering Implementation
        
        // Current filters state
        let activeFilters = {
          category: '{{ request('category') }}',
          price: '{{ request('price') }}',
          condition: '{{ request('condition') }}',
          sort: '{{ request('sort') || 'newest' }}',
          search: '{{ request('search') }}'
        };
        
        // Store current page URL to help prevent unwanted redirects
        let currentPageUrl = window.location.href;
        
        // Store current category name
        let currentCategoryName = '{{ request('category') ? $categories->find(request('category'))->name : 'All Products' }}';
        
        // Initialize nav menu - call this once at page load
        ensureSingleActiveCategory();
        
        // Listen for filter changes - using direct event binding
        $(document).on('change', '.ajax-filter', function(e) {
          e.preventDefault(); // Prevent any default actions
          const filterType = $(this).data('filter');
          const filterValue = $(this).val();
          
          console.log(`Filter changed: ${filterType} = ${filterValue}`);
          
          // If changing category, store the new category name
          if (filterType === 'category') {
            if (filterValue === '') {
              currentCategoryName = 'All Products';
            } else {
              currentCategoryName = $(this).find('option:selected').text().trim();
            }
            console.log(`New category name: ${currentCategoryName}`);
            // Reset navigation active state completely and set new active state
            setSingleActiveCategory(filterValue);
          }
          
          // Update active filters
          activeFilters[filterType] = filterValue;

          // Always update sort filter to current value
          activeFilters['sort'] = $('select[data-filter="sort"]').val();
          
          // Fetch products with new filters
          const newUrl = buildFilterUrl(activeFilters);
          fetchFilteredProducts(newUrl, true); // Pass true to indicate this is a filter change
          
          // Update heading immediately to prevent flicker
          $('.filter-section h2').text(currentCategoryName);
          
          return false; // Prevent form submission
        });
        
        // Clear individual filters
        $(document).on('click', '.clear-filter', function() {
          const filterType = $(this).data('filter');
          
          console.log(`Clearing filter: ${filterType}`);
          
          // Update active filters
          activeFilters[filterType] = '';
          
          // Reset the corresponding select element
          $(`select[data-filter="${filterType}"]`).val('');
          
          // Fetch products with updated filters
          fetchFilteredProducts(buildFilterUrl(activeFilters));
        });
        
        // Handle navigation links with AJAX
        $(document).on('click', '.nav-ajax-link', function(e) {
          e.preventDefault();
          
          // Don't process if we're already handling a navigation update
          if (isUpdatingNavigation) return false;
          
          const url = $(this).data('url') || $(this).attr('href');
          console.log(`Nav link clicked: ${url}`);
          
          // Parse the URL to extract the category parameter
          let categoryParam = '';
          if (url.includes('category=')) {
            const urlParams = new URLSearchParams(url.split('?')[1]);
            categoryParam = urlParams.get('category');
          }
          
          // Reset all navigation items first
          $('.navbar-nav .nav-item').removeClass('active');
          
          // Set only this one as active
          $(this).closest('.nav-item').addClass('active');
          
          // Store the category name
          if (categoryParam) {
            currentCategoryName = $(this).text().trim();
          } else {
            currentCategoryName = 'All Products';
          }
          console.log(`Category name from nav: ${currentCategoryName}`);
          
          // Reset all active filters except the selected category
          activeFilters = {
            category: categoryParam,
            price: '',
            condition: '',
            sort: 'newest',
            search: ''
          };
          
          // Update heading immediately to prevent flicker
          $('.filter-section h2').text(currentCategoryName);
          
          // Fetch products with updated filters
          fetchFilteredProducts(url, true);
          
          // Show filter section when a category is selected
          if (categoryParam || categoryParam === '') {
            filterSection.style.display = 'block';
            if (filterToggleBtn) {
              filterToggleBtn.innerHTML = '<i class="fas fa-filter"></i> Hide Filters';
            }
          }
          
          return false; // Prevent default navigation
        });
        
        // Handle pagination links
        $(document).on('click', '#pagination-container a', function(e) {
          e.preventDefault();
          let url = $(this).attr('href');
          
          console.log(`Pagination link clicked: ${url}`);
          
          fetchFilteredProducts(url);
        });
        
        // Handle browser back/forward buttons
        window.addEventListener('popstate', function(event) {
          console.log("Browser navigation detected", event.state);
          
          // Check if this popstate event was triggered by our AJAX navigation
          if (event.state && event.state.ajaxFilter) {
            // Load the page content via AJAX without pushing a new history state
            fetchFilteredProducts(event.state.url || window.location.href, false);
          } else {
            // Don't do anything - let the browser handle the navigation
            // This prevents infinite loops with browser back/forward buttons
          }
        });
        
        // Attach a global AJAX event handler to catch errors
        $(document).ajaxError(function(event, jqxhr, settings, thrownError) {
          console.error('AJAX error:', thrownError, jqxhr.status, settings.url);
          // Don't reload on AJAX errors, just hide spinner
          $('#loading-spinner').hide();
        });
        
        // --- AUTO-LOAD PRODUCTS BY CURRENT LOCATION ON FIRST LOAD (ONLY FOR LOGGED IN USERS) ---
        const isLoggedIn = {{ auth()->check() ? 'true' : 'false' }};
        const hasAnyFilters = Boolean(activeFilters.category || activeFilters.price || activeFilters.condition || activeFilters.search);
        const locationLoadedKey = 'locationProductsLoaded';
        if (isLoggedIn && !hasAnyFilters && !sessionStorage.getItem(locationLoadedKey)) {
          if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
              const lat = position.coords.latitude;
              const lng = position.coords.longitude;
              // Mark as loaded so we don't repeat
              sessionStorage.setItem(locationLoadedKey, '1');
              // Add lat/lng to filters
              activeFilters.lat = lat;
              activeFilters.lng = lng;
              // Build URL and fetch
              const url = buildFilterUrl(activeFilters);
              fetchFilteredProducts(url, true);
            }, function(error) {
              // User denied or error, do nothing
              sessionStorage.setItem(locationLoadedKey, '1');
            });
          } else {
            // Geolocation not supported
            sessionStorage.setItem(locationLoadedKey, '1');
          }
        }
      });
      
      function buildFilterUrl(filters) {
        const baseUrl = '{{ route('products.index') }}';
        let params = [];
        
        Object.keys(filters).forEach(key => {
          if (filters[key]) {
            params.push(`${key}=${encodeURIComponent(filters[key])}`);
          }
        });
        
        const url = params.length ? `${baseUrl}?${params.join('&')}` : baseUrl;
        console.log("Built URL:", url);
        return url;
      }
      
      function fetchFilteredProducts(url, updateHistory = false) {
        // Show loading spinner
        $('#loading-spinner').show();
        
        console.log("Fetching products from:", url);
        
        // First, try a HEAD request to check for potential redirects
        $.ajax({
          url: url,
          type: 'HEAD',
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          },
          complete: function(xhr) {
            // Check if the URL would redirect
            const finalUrl = xhr.getResponseHeader('X-Final-URL') || url;
            
            // Now make the actual GET request
            $.ajax({
              url: url,
              type: 'GET',
              headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-AJAX-Filter': 'true', // Custom header to identify filter requests
                'Cache-Control': 'no-cache'
              },
              dataType: 'html', // Explicitly request HTML
              success: function(response) {
                console.log("AJAX request successful");
                
                // Only update browser history if requested (e.g., for filter changes)
                if (updateHistory) {
                  // Update URL without a page refresh
                  history.pushState({ ajaxFilter: true, url: url }, '', url);
                }
                
                // Extract and update product list
                updateProductsFromResponse(response);
                
                // Hide loading spinner
                $('#loading-spinner').hide();
                
                // Reinitialize filter dropdowns
                reinitializeFilters(url);
              },
              error: function(xhr, status, error) {
                console.error("AJAX request failed:", status, error);
                
                // Don't redirect, just show an error and hide spinner
                $('#loading-spinner').hide();
                alert("There was an error loading products. Please try again.");
              }
            });
          }
        });
      }
      
      function updateProductsFromResponse(response) {
        // Check if the response is a complete HTML page or just a fragment
        const isFullPage = response.toLowerCase().includes('<html') && response.toLowerCase().includes('</html>');
        
        let productListContent;
        let paginationContent;
        
        // Don't update the category heading from response to preserve our current category
        // This is key to fixing the issue
        
        if (isFullPage) {
          // Parse the HTML response
          const parser = new DOMParser();
          const doc = parser.parseFromString(response, 'text/html');
          
          // Extract product list
          const productList = doc.getElementById('product-list-container');
          productListContent = productList ? productList.innerHTML : null;
          
          // Extract pagination
          const pagination = doc.getElementById('pagination-container');
          paginationContent = pagination ? pagination.innerHTML : null;
        } else {
          // The response is already the fragment we need
          productListContent = response;
        }
        
        // Update the product list if content was found
        if (productListContent) {
          $('#product-list-container').html(productListContent);
          console.log("Product list updated");
        } else {
          console.warn("Product list container not found in response");
          
          // Try to extract products from the response in a different way
          const tempDiv = $('<div></div>').html(response);
          const productItems = tempDiv.find('.product-list li');
          
          if (productItems.length) {
            $('#product-list-container').html(productItems);
            console.log(`Found ${productItems.length} products`);
          } else {
            // No products found
            $('#product-list-container').html('<div class="col-12"><div class="alert alert-info">No products found. Try adjusting your search criteria.</div></div>');
            console.log("No products found");
          }
        }
        
        // Update pagination if content was found
        if (paginationContent) {
          $('#pagination-container').html(paginationContent);
          console.log("Pagination updated");
        } else {
          // Try to find pagination in a different way
          const tempDiv = $('<div></div>').html(response);
          const paginationLinks = tempDiv.find('.pagination');
          
          if (paginationLinks.length) {
            $('#pagination-container').html(paginationLinks);
            console.log("Pagination found and updated");
          } else {
            $('#pagination-container').empty();
            console.log("No pagination found");
          }
        }
        
        // Reinitialize click handlers for product items
        $('#product-list-container li').css('cursor', 'pointer').on('click', function(e) {
          // If the click is on a link, let it handle naturally
          if ($(e.target).is('a') || $(e.target).parents('a').length) {
            return;
          }
          
          const productLink = $(this).find('a').first().attr('href');
          if (productLink) {
            window.location = productLink;
          }
        });
      }
      
      function reinitializeFilters(url) {
        // Parse URL to extract current filter values
        const urlParams = new URLSearchParams(url.split('?')[1] || '');
        
        // Update category filter if present - but don't change the heading
        const category = urlParams.get('category');
        
        // Set the category filter dropdown value
        if (category) {
          $('select[data-filter="category"]').val(category);
        } else {
          $('select[data-filter="category"]').val('');
        }
        
        // Update navigation menu active state - directly set active category
        setSingleActiveCategory(category);
        
        // Update price filter if present
        const price = urlParams.get('price');
        if (price) {
          $('select[data-filter="price"]').val(price);
        } else {
          $('select[data-filter="price"]').val('');
        }
        
        // Update condition filter if present
        const condition = urlParams.get('condition');
        if (condition) {
          $('select[data-filter="condition"]').val(condition);
          // Show the clear filter button
          if ($('.clear-filter[data-filter="condition"]').length === 0) {
            $('select[data-filter="condition"]').after('<a href="javascript:void(0)" class="clear-filter" data-filter="condition" title="Clear condition filter"></a>');
          }
        } else {
          // Hide the clear filter button if no condition is selected
          $('.clear-filter[data-filter="condition"]').remove();
          $('select[data-filter="condition"]').val('');
        }
        
        // Update sort filter if present
        const sort = urlParams.get('sort');
        if (sort) {
          $('select[data-filter="sort"]').val(sort);
        } else {
          $('select[data-filter="sort"]').val('newest');
        }
        
        console.log("Filters reinitialized");
      }
      
      // New function to set a single active category
      function setSingleActiveCategory(categoryId) {
        console.log(`Setting single active category: ${categoryId || 'All'}`);
        
        // First, remove active class from ALL navigation items
        $('.navbar-nav .nav-item').removeClass('active');
        
        // Then set the appropriate one active
        if (!categoryId) {
          // If no category, set "All Items" active
          $('.navbar-nav .nav-item:first-child').addClass('active');
        } else {
          // Find exact match for this category ID
          const selector = `.navbar-nav .nav-item a[href$="category=${categoryId}"]`;
          const exactMatch = $(selector);
          
          if (exactMatch.length) {
            exactMatch.closest('.nav-item').addClass('active');
          } else {
            // If no match found, default to "All Items"
            $('.navbar-nav .nav-item:first-child').addClass('active');
          }
        }
        
        // Verify we only have one active item
        ensureSingleActiveCategory();
      }
      
      // Function to ensure only one category is active
      function ensureSingleActiveCategory() {
        // Check if we have multiple active categories
        const activeItems = $('.navbar-nav .nav-item.active');
        if (activeItems.length > 1) {
          console.warn('Multiple active categories detected, fixing...');
          // Keep only the first one
          activeItems.not(':first').removeClass('active');
        } else if (activeItems.length === 0) {
          console.warn('No active category, setting All Items as active');
          // If no active category, set All Items as active
          $('.navbar-nav .nav-item:first-child').addClass('active');
        }
      }
      
      // Replace the previous updateNavigationActiveState with our new functions
      function updateNavigationActiveState(categoryId, fromDropdown = false) {
        setSingleActiveCategory(categoryId);
      }
      
      // Global function to handle category click from navigation
      function handleCategoryClick(element) {
        // This function is called from the onclick attribute in the HTML
        console.log("Category clicked:", element);
        
        // First, remove active class from all categories
        $('.navbar-nav .nav-item').removeClass('active');
        
        // Set only this one as active
        $(element).closest('.nav-item').addClass('active');
        
        // Now trigger the click
        $(element).trigger('click');
      }
    </script>
    
    <script>
       $(document).ready(function(){
        $(".navbar-toggler").on("click", function(){
            $("body").addClass("show");
        });
          $(".close-btn").on("click", function(){
             $(".navbar-collapse").removeClass("show");
          });
          $(".close-btn").on("click", function(){
             $("body").removeClass("show");
          });
      });
    </script>
    @endpush
@endsection
