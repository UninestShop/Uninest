@extends('admin.layouts.app', ['title' => 'Edit User'])

@section('content')
<head>

    <style>
        .phone-input-container .iti {
          width: 100%;
        }
        .iti__flag-container {
          z-index: 99;
        }
      </style>
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Edit User: {{ $user->name }}</h5>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to List
                </a>
            </div>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show m-3" role="alert">
                <i class="fas fa-info-circle me-1"></i> {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        <div class="card-body">
            
            <form action="{{ url('/admin/users/'.$user->slug) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror bg-light" 
                           id="email" name="email" value="{{ old('email', $user->email) }}" disabled required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- <div class="col-sm-12 mb-3">
                    <label>Contact Number <span>*</span></label>
                    <input type="tel" name="mobile_number" class="form-control" value="{{ old('mobile_number', $user->mobile_number) }}" 
                     inputmode="numeric" maxlength="13"
                     placeholder="Enter number starting with country code">
                    @error('mobile_number')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                    </div> --}}
                    <div class="col-sm-12 mb-3">
                    <label>Contact Number <span>*</span></label>
                    <div class="phone-input-container">
                      <input type="tel" id="phone" name="mobile_number" class="form-control" 
                      value="{{ old('mobile_number', $user->mobile_number) }}"
                      pattern="[0-9]{8,13}" inputmode="numeric" minlength="8" maxlength="13"
                      oninput="this.value = this.value.replace(/[^0-9]/g, ''); validatePhoneLength(this);">
                      <input type="hidden" id="country_code" name="country_code" value="{{ old('country_code', $user->country_code) }}">
                      <input type="hidden" id="country_iso" name="country_iso" value="{{ old('country_iso', $user->country_iso ?? '') }}">
                    </div>
                    <span class="text-danger phone-error" style="display:none;">Phone number must be between 8 and 13 digits</span>
                    @error('mobile_number')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                    </div>

                  <div class="col-sm-12 mb-3">
                    <label>Gender <span>*</span></label>
                    <select class="form-control" name="gender" disabled>
                      <option value="Male" {{ old('gender', $user->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                      <option value="Female" {{ old('gender', $user->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                      <option value="Other" {{ old('gender', $user->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('gender')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                  <div class="col-sm-12 mb-3">
                    <label>Date of Birth <span>*</span></label>
                    <input type="date" name="dob" id="dob" class="form-control" value="{{ old('dob', $user->dob) }}" max="{{ date('Y-m-d', strtotime('-18 years')) }}">
                    <span class="text-danger" id="dob-error"></span>
                    @error('dob')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>    
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
 <script>
          function validatePhoneLength(input) {
            const errorElement = document.querySelector('.phone-error');
            if (input.value.length < 8 || input.value.length > 13) {
            errorElement.style.display = 'block';
            input.setCustomValidity('Phone number must be between 8 and 13 digits');
            } else {
            errorElement.style.display = 'none';
            input.setCustomValidity('');
            }
          }

          // Validate on page load
          document.addEventListener('DOMContentLoaded', function() {
            const phoneInput = document.getElementById('phone');
            validatePhoneLength(phoneInput);
            
            // Add form submission check
            document.querySelector('form').addEventListener('submit', function(e) {
            validatePhoneLength(phoneInput);
            if (phoneInput.value.length < 8 || phoneInput.value.length > 13) {
              e.preventDefault();
            }
            });
          });
</script>
<script>
    // Show/hide new type field based on selection
    document.getElementById('user_type').addEventListener('change', function() {
        const newTypeContainer = document.getElementById('new_type_container');
        if (this.value === 'new_type') {
            newTypeContainer.style.display = 'block';
        } else {
            newTypeContainer.style.display = 'none';
        }
    });
    
    // Trigger change event on page load to handle initial state
    document.addEventListener('DOMContentLoaded', function() {
        const userType = document.getElementById('user_type');
        if (userType.value === 'new_type') {
            document.getElementById('new_type_container').style.display = 'block';
        }
    });
    
    // Form submission handler for new type
    document.querySelector('form').addEventListener('submit', function(e) {
        const userType = document.getElementById('user_type');
        const newUserType = document.getElementById('new_user_type');
        
        if (userType.value === 'new_type' && newUserType.value.trim() === '') {
            e.preventDefault();
            alert('Please enter a name for the new user type');
            newUserType.focus();
        } else if (userType.value === 'new_type') {
            userType.value = newUserType.value.trim().toLowerCase().replace(/ /g, '_');
        }
    });

    // Debugging form submission
    document.querySelector('form').addEventListener('submit', function(e) {
        // Remove existing debug code for user_type since it doesn't exist in this form anymore
        console.log('Form submitted');
        console.log('Mobile number:', document.querySelector('input[name="mobile_number"]').value);
        console.log('DOB:', document.querySelector('input[name="dob"]').value);
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Display current form values for debugging
        var mobileField = document.querySelector('input[name="mobile_number"]');
        var dobField = document.querySelector('input[name="dob"]');
        
        console.log('Initial mobile_number value:', mobileField.value);
        console.log('Initial dob value:', dobField.value);
        
        // Add form submission monitoring
        document.querySelector('form').addEventListener('submit', function(e) {
            console.log('Form submitted with:');
            console.log('- mobile_number:', mobileField.value);
            console.log('- dob:', dobField.value);
        });
    });

    // DOB validation for 18+ years
    document.addEventListener('DOMContentLoaded', function() {
        const dobInput = document.getElementById('dob');
        const dobError = document.getElementById('dob-error');
        const form = document.querySelector('form');
        
        // Set max date attribute to 18 years ago
        const today = new Date();
        const eighteenYearsAgo = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());
        const maxDate = eighteenYearsAgo.toISOString().split('T')[0];
        dobInput.setAttribute('max', maxDate);
        
        // Validate on input change
        dobInput.addEventListener('input', validateAge);
        
        // Validate on form submission
        form.addEventListener('submit', function(e) {
            if (!validateAge()) {
                e.preventDefault();
            }
        });
        
        function validateAge() {
            const selectedDate = new Date(dobInput.value);
            
            if (isNaN(selectedDate.getTime())) {
                dobError.textContent = 'Please select a valid date';
                return false;
            }
            
            if (selectedDate > eighteenYearsAgo) {
                dobError.textContent = 'You must be at least 18 years old';
                return false;
            }
            
            dobError.textContent = '';
            return true;
        }
        
        // Validate initially if a value exists
        if (dobInput.value) {
            validateAge();
        }
    });

</script>



<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/css/intlTelInput.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/intlTelInput.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const input = document.querySelector("#phone");
    const countryCodeInput = document.querySelector("#country_code");
    const countryIsoInput = document.querySelector("#country_iso");
    let iti;
    
    // Common dial code to country ISO mappings
    const dialCodeToCountry = {
                          '1': 'us',     // United States/Canada
                          '7': 'ru',     // Russia
                          '20': 'eg',    // Egypt
                          '27': 'za',    // South Africa
                          '30': 'gr',    // Greece
                          '31': 'nl',    // Netherlands
                          '32': 'be',    // Belgium
                          '33': 'fr',    // France
                          '34': 'es',    // Spain
                          '36': 'hu',    // Hungary
                          '39': 'it',    // Italy
                          '40': 'ro',    // Romania
                          '41': 'ch',    // Switzerland
                          '43': 'at',    // Austria
                          '44': 'gb',    // United Kingdom
                          '45': 'dk',    // Denmark
                          '46': 'se',    // Sweden
                          '47': 'no',    // Norway
                          '48': 'pl',    // Poland
                          '49': 'de',    // Germany
                          '51': 'pe',    // Peru
                          '52': 'mx',    // Mexico
                          '53': 'cu',    // Cuba
                          '54': 'ar',    // Argentina
                          '55': 'br',    // Brazil
                          '56': 'cl',    // Chile
                          '57': 'co',    // Colombia
                          '58': 've',    // Venezuela
                          '60': 'my',    // Malaysia
                          '61': 'au',    // Australia
                          '62': 'id',    // Indonesia
                          '63': 'ph',    // Philippines
                          '64': 'nz',    // New Zealand
                          '65': 'sg',    // Singapore
                          '66': 'th',    // Thailand
                          '81': 'jp',    // Japan
                          '82': 'kr',    // South Korea
                          '84': 'vn',    // Vietnam
                          '86': 'cn',    // China
                          '90': 'tr',    // Turkey
                          '91': 'in',    // India
                          '92': 'pk',    // Pakistan
                          '93': 'af',    // Afghanistan
                          '94': 'lk',    // Sri Lanka
                          '95': 'mm',    // Myanmar
                          '98': 'ir',    // Iran
                          '212': 'ma',   // Morocco
                          '213': 'dz',   // Algeria
                          '216': 'tn',   // Tunisia
                          '218': 'ly',   // Libya
                          '220': 'gm',   // Gambia
                          '221': 'sn',   // Senegal
                          '222': 'mr',   // Mauritania
                          '223': 'ml',   // Mali
                          '224': 'gn',   // Guinea
                          '225': 'ci',   // Ivory Coast
                          '226': 'bf',   // Burkina Faso
                          '227': 'ne',   // Niger
                          '228': 'tg',   // Togo
                          '229': 'bj',   // Benin
                          '230': 'mu',   // Mauritius
                          '231': 'lr',   // Liberia
                          '232': 'sl',   // Sierra Leone
                          '233': 'gh',   // Ghana
                          '234': 'ng',   // Nigeria
                          '235': 'td',   // Chad
                          '236': 'cf',   // Central African Republic
                          '237': 'cm',   // Cameroon
                          '238': 'cv',   // Cape Verde
                          '239': 'st',   // São Tomé and Príncipe
                          '240': 'gq',   // Equatorial Guinea
                          '241': 'ga',   // Gabon
                          '242': 'cg',   // Congo
                          '243': 'cd',   // Democratic Republic of the Congo
                          '244': 'ao',   // Angola
                          '245': 'gw',   // Guinea-Bissau
                          '246': 'io',   // British Indian Ocean Territory
                          '248': 'sc',   // Seychelles
                          '249': 'sd',   // Sudan
                          '250': 'rw',   // Rwanda
                          '251': 'et',   // Ethiopia
                          '252': 'so',   // Somalia
                          '253': 'dj',   // Djibouti
                          '254': 'ke',   // Kenya
                          '255': 'tz',   // Tanzania
                          '256': 'ug',   // Uganda
                          '257': 'bi',   // Burundi
                          '258': 'mz',   // Mozambique
                          '260': 'zm',   // Zambia
                          '261': 'mg',   // Madagascar
                          '262': 're',   // Réunion
                          '263': 'zw',   // Zimbabwe
                          '264': 'na',   // Namibia
                          '265': 'mw',   // Malawi
                          '266': 'ls',   // Lesotho
                          '267': 'bw',   // Botswana
                          '268': 'sz',   // Eswatini
                          '269': 'km',   // Comoros
                          '297': 'aw',   // Aruba
                          '298': 'fo',   // Faroe Islands
                          '299': 'gl',   // Greenland
                          '350': 'gi',   // Gibraltar
                          '351': 'pt',   // Portugal
                          '352': 'lu',   // Luxembourg
                          '353': 'ie',   // Ireland
                          '354': 'is',   // Iceland
                          '355': 'al',   // Albania
                          '356': 'mt',   // Malta
                          '357': 'cy',   // Cyprus
                          '358': 'fi',   // Finland
                          '359': 'bg',   // Bulgaria
                          '370': 'lt',   // Lithuania
                          '371': 'lv',   // Latvia
                          '372': 'ee',   // Estonia
                          '373': 'md',   // Moldova
                          '374': 'am',   // Armenia
                          '375': 'by',   // Belarus
                          '376': 'ad',   // Andorra
                          '377': 'mc',   // Monaco
                          '378': 'sm',   // San Marino
                          '379': 'va',   // Vatican City
                          '380': 'ua',   // Ukraine
                          '381': 'rs',   // Serbia
                          '382': 'me',   // Montenegro
                          '383': 'xk',   // Kosovo
                          '385': 'hr',   // Croatia
                          '386': 'si',   // Slovenia
                          '387': 'ba',   // Bosnia and Herzegovina
                          '389': 'mk',   // North Macedonia
                          '420': 'cz',   // Czech Republic
                          '421': 'sk',   // Slovakia
                          '423': 'li',   // Liechtenstein
                          '500': 'fk',   // Falkland Islands
                          '501': 'bz',   // Belize
                          '502': 'gt',   // Guatemala
                          '503': 'sv',   // El Salvador
                          '504': 'hn',   // Honduras
                          '505': 'ni',   // Nicaragua
                          '506': 'cr',   // Costa Rica
                          '507': 'pa',   // Panama
                          '508': 'pm',   // Saint Pierre and Miquelon
                          '509': 'ht',   // Haiti
                          '590': 'gp',   // Guadeloupe
                          '591': 'bo',   // Bolivia
                          '592': 'gy',   // Guyana
                          '593': 'ec',   // Ecuador
                          '594': 'gf',   // French Guiana
                          '595': 'py',   // Paraguay
                          '596': 'mq',   // Martinique
                          '597': 'sr',   // Suriname
                          '598': 'uy',   // Uruguay
                          '599': 'cw',   // Curaçao
                          '670': 'tl',   // Timor-Leste
                          '672': 'nf',   // Norfolk Island
                          '673': 'bn',   // Brunei
                          '674': 'nr',   // Nauru
                          '675': 'pg',   // Papua New Guinea
                          '676': 'to',   // Tonga
                          '677': 'sb',   // Solomon Islands
                          '678': 'vu',   // Vanuatu
                          '679': 'fj',   // Fiji
                          '680': 'pw',   // Palau
                          '681': 'wf',   // Wallis and Futuna
                          '682': 'ck',   // Cook Islands
                          '683': 'nu',   // Niue
                          '685': 'ws',   // Samoa
                          '686': 'ki',   // Kiribati
                          '687': 'nc',   // New Caledonia
                          '688': 'tv',   // Tuvalu
                          '689': 'pf',   // French Polynesia
                          '690': 'tk',   // Tokelau
                          '691': 'fm',   // Micronesia
                          '692': 'mh',   // Marshall Islands
                          '850': 'kp',   // North Korea
                          '852': 'hk',   // Hong Kong
                          '853': 'mo',   // Macau
                          '855': 'kh',   // Cambodia
                          '856': 'la',   // Laos
                          '880': 'bd',   // Bangladesh
                          '886': 'tw',   // Taiwan
                          '960': 'mv',   // Maldives
                          '961': 'lb',   // Lebanon
                          '962': 'jo',   // Jordan
                          '963': 'sy',   // Syria
                          '964': 'iq',   // Iraq
                          '965': 'kw',   // Kuwait
                          '966': 'sa',   // Saudi Arabia
                          '967': 'ye',   // Yemen
                          '968': 'om',   // Oman
                          '970': 'ps',   // Palestine
                          '971': 'ae',   // United Arab Emirates
                          '972': 'il',   // Israel
                          '973': 'bh',   // Bahrain
                          '974': 'qa',   // Qatar
                          '975': 'bt',   // Bhutan
                          '976': 'mn',   // Mongolia
                          '977': 'np',   // Nepal
                          '992': 'tj',   // Tajikistan
                          '993': 'tm',   // Turkmenistan
                          '994': 'az',   // Azerbaijan
                          '995': 'ge',   // Georgia
                          '996': 'kg',   // Kyrgyzstan
                          '998': 'uz'    // Uzbekistan
                        };
    
    // Determine initial country
    let initialCountry = 'us';
    const storedCountryCode = countryCodeInput.value || '';
    const storedCountryIso = countryIsoInput.value || '';
    
    if (storedCountryIso) {
      // If we have ISO country code stored, use it directly
      initialCountry = storedCountryIso.toLowerCase();
    } else if (storedCountryCode && dialCodeToCountry[storedCountryCode]) {
      // If we only have dial code, map it to a country code
      initialCountry = dialCodeToCountry[storedCountryCode];
      // Store the mapped ISO code
      countryIsoInput.value = initialCountry;
    }
    
    // Initialize intl-tel-input with determined country
    iti = window.intlTelInput(input, {
      utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/utils.js",
      initialCountry: initialCountry,
      separateDialCode: true,
      preferredCountries: ['us', 'gb', 'ca', 'au'],
      geoIpLookup: function(callback) {
        // Only use geoIpLookup if we don't have stored country data
        if (!storedCountryCode && !storedCountryIso) {
          fetch("https://ipapi.co/json")
            .then(res => res.json())
            .then(data => callback(data.country_code))
            .catch(() => callback(initialCountry));
        } else {
          callback(initialCountry);
        }
      }
    });
    
    // Function to update country code and ISO
    function updateCountryData() {
      const selectedCountryData = iti.getSelectedCountryData();
      if (selectedCountryData) {
        if (selectedCountryData.dialCode) {
          countryCodeInput.value = selectedCountryData.dialCode;
        }
        if (selectedCountryData.iso2) {
          countryIsoInput.value = selectedCountryData.iso2;
        }
        console.log("Country data updated:", selectedCountryData);
      }
    }
    
    // Listen for country changes
    input.addEventListener('countrychange', updateCountryData);
    
    // Ensure country data is captured before form submission
    document.querySelector('form').addEventListener('submit', function(e) {
      updateCountryData();
    });

    // Set initial country data
    setTimeout(function() {
      updateCountryData();
    }, 500);
  });
</script>
@endpush
@endsection
