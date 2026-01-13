@extends('layouts.app')
@php
    $unreadChatCount = 0;
    
    if (isset($chats)) {
      
        foreach ($chats as $productId => $productChats) {
            foreach ($productChats as $chat) {
                if ($chat->receiver_id === auth()->id() && !$chat->is_read) {
                    $unreadChatCount++;
                }
            }
        }
    }
@endphp
@section('content')
    <div class="myprofile-main">
      <div class="container">
        <div class="profile-view">
          <div class="profile-left">
            <div class="profile-top">
                
                
                  <div class="profile-name">
                    <figure>
                      <img src="{{ auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : asset('images/profile-img.png') }}" alt="profile">
                      <a href="#" class="upload-profile">
                        <form action="{{ route('profile.update.image') }}" method="POST" enctype="multipart/form-data">
                          @csrf
                          <input type="file" name="profile_picture" class="form-control" onchange="this.form.submit()">
                          <img src="{{ asset('images/Camera-icon.png') }}" alt="upload-profile">
                        </form>
                      </a>
                    </figure>
                    <h2>{{ auth()->user()->name }}</h2>
                    <p>{{ auth()->user()->email }}</p>
                  </div>
                
                <ul class="profile-list">
                    <li><a href="{{ route('profile.show','#profileContent') }}" class="pro-links {{ request()->routeIs('profile.show') ? 'active' : '' }}"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12.0003 13.4102C13.2893 13.4102 14.4807 13.0469 15.4339 12.4297C16.1721 11.957 17.1331 12.0352 17.7815 12.625C19.5823 14.2578 20.6057 16.5742 20.6018 19.0078V20.0508C20.6018 21.1289 19.7268 22 18.6487 22H5.35186C4.27373 22 3.39873 21.1289 3.39873 20.0508V19.0078C3.39091 16.5781 4.41435 14.2578 6.21514 12.6289C6.86358 12.0391 7.82842 11.9609 8.5628 12.4336C9.51983 13.0469 10.7073 13.4102 12.0003 13.4102Z" fill="#6A6F74"/>
                        <path d="M12.0007 11.6719C14.6716 11.6719 16.8367 9.50676 16.8367 6.83594C16.8367 4.16513 14.6716 2 12.0007 2C9.32992 2 7.16479 4.16513 7.16479 6.83594C7.16479 9.50676 9.32992 11.6719 12.0007 11.6719Z" fill="#6A6F74"/>
                        </svg> Profile information</a></li>
                    <li><a href="{{ route('seller.products','#mypro-content') }}" class="pro-links {{ request()->routeIs('seller.products.index') ? 'active' : '' }}"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18.5 6.26008C18.773 5.80929 18.9437 5.30406 19 4.78008C19.0794 3.89214 18.8111 3.00803 18.2514 2.31409C17.6918 1.62015 16.8846 1.17056 16 1.06008C15.4773 0.948006 14.9359 0.955449 14.4165 1.08185C13.897 1.20826 13.4128 1.45035 13 1.79008C12.5908 2.15162 12.2518 2.58555 12 3.07008C11.7482 2.58555 11.4092 2.15162 11 1.79008C10.5872 1.45035 10.103 1.20826 9.58352 1.08185C9.06405 0.955449 8.52275 0.948006 8 1.06008C7.11539 1.17056 6.30821 1.62015 5.74858 2.31409C5.18894 3.00803 4.92056 3.89214 5 4.78008C5.05633 5.30406 5.22701 5.80929 5.5 6.26008C4.48985 6.57773 3.60663 7.2079 2.97762 8.05976C2.34861 8.91161 2.0063 9.94118 2 11.0001V18.0001C2 18.6567 2.12933 19.3069 2.3806 19.9135C2.63188 20.5201 3.00017 21.0713 3.46447 21.5356C4.40215 22.4733 5.67392 23.0001 7 23.0001H17C17.6566 23.0001 18.3068 22.8708 18.9134 22.6195C19.52 22.3682 20.0712 21.9999 20.5355 21.5356C20.9998 21.0713 21.3681 20.5201 21.6194 19.9135C21.8707 19.3069 22 18.6567 22 18.0001V11.0001C21.9937 9.94118 21.6514 8.91161 21.0224 8.05976C20.3934 7.2079 19.5102 6.57773 18.5 6.26008ZM14.31 3.33008C14.4931 3.17598 14.7092 3.06607 14.9415 3.00884C15.1739 2.95162 15.4163 2.94862 15.65 3.00008C15.8471 3.00952 16.0402 3.05967 16.217 3.14736C16.3938 3.23506 16.5505 3.3584 16.6773 3.50963C16.8042 3.66086 16.8983 3.83671 16.9538 4.02609C17.0093 4.21548 17.0251 4.41432 17 4.61008C16.9826 4.81412 16.9244 5.0126 16.8289 5.19374C16.7334 5.37489 16.6025 5.53502 16.444 5.66464C16.2855 5.79427 16.1025 5.89075 15.906 5.94837C15.7095 6.00599 15.5034 6.02357 15.3 6.00008H13.11C13.2135 5.00447 13.6342 4.06847 14.31 3.33008ZM8.31 3.03008C8.43981 3.02016 8.57019 3.02016 8.7 3.03008C9.04589 3.02322 9.38284 3.14027 9.65 3.36008C10.3346 4.08512 10.7691 5.01026 10.89 6.00008H8.7C8.49657 6.02357 8.29049 6.00599 8.09399 5.94837C7.89748 5.89075 7.71453 5.79427 7.556 5.66464C7.39747 5.53502 7.26657 5.37489 7.17106 5.19374C7.07556 5.0126 7.01739 4.81412 7 4.61008C6.97645 4.41457 6.99325 4.2163 7.04937 4.02754C7.10548 3.83878 7.19971 3.66354 7.32624 3.51264C7.45277 3.36174 7.6089 3.2384 7.78499 3.15024C7.96108 3.06208 8.15338 3.01097 8.35 3.00008L8.31 3.03008ZM17 19.0001H13C12.7348 19.0001 12.4804 18.8947 12.2929 18.7072C12.1054 18.5196 12 18.2653 12 18.0001C12 17.7349 12.1054 17.4805 12.2929 17.293C12.4804 17.1054 12.7348 17.0001 13 17.0001H17C17.2652 17.0001 17.5196 17.1054 17.7071 17.293C17.8946 17.4805 18 17.7349 18 18.0001C18 18.2653 17.8946 18.5196 17.7071 18.7072C17.5196 18.8947 17.2652 19.0001 17 19.0001Z" fill="#6A6F74"/>
                        </svg> My Products</a></li>
                    <li><a href="{{ route('seller.products.create','#list-product') }}" class="pro-links {{ request()->routeIs('seller.products.create') ? 'active' : '' }}"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M21.0987 6.68896H10.031C9.67062 6.68896 9.46549 6.96759 9.57237 7.31184L11.3232 12.4407C11.4095 12.7175 11.7147 12.9417 12.0046 12.9417H19.2609C19.5507 12.9417 19.8556 12.7175 19.9415 12.4407L21.4674 7.19071C21.554 6.91396 21.389 6.68896 21.0987 6.68896Z" fill="#6A6F74"/>
                        <path d="M11.0317 20.5058C11.9461 20.5058 12.6873 19.7646 12.6873 18.8502C12.6873 17.9358 11.9461 17.1946 11.0317 17.1946C10.1173 17.1946 9.3761 17.9358 9.3761 18.8502C9.3761 19.7646 10.1173 20.5058 11.0317 20.5058Z" fill="#6A6F74"/>
                        <path d="M18.0376 20.5268C18.952 20.5268 19.6932 19.7856 19.6932 18.8712C19.6932 17.9568 18.952 17.2156 18.0376 17.2156C17.1232 17.2156 16.382 17.9568 16.382 18.8712C16.382 19.7856 17.1232 20.5268 18.0376 20.5268Z" fill="#6A6F74"/>
                        <path d="M20.025 14.6265H10.3421L6.46576 3.4729H3.44739C2.92951 3.4729 2.50989 3.89253 2.50989 4.4104C2.50989 4.92828 2.92951 5.3479 3.44739 5.3479H5.13226L9.00864 16.5015H20.025C20.5429 16.5015 20.9625 16.0819 20.9625 15.564C20.9625 15.0462 20.5429 14.6265 20.025 14.6265Z" fill="#6A6F74"/>
                        </svg> Sell an Item</a></li>
                    <li><a href="{{ route('messages.index','#profileRight') }}" class="pro-links pro-linkss {{ request()->routeIs('messages.*') ? 'active' : '' }}"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M20 3H4C3.20435 3 2.44129 3.31607 1.87868 3.87868C1.31607 4.44129 1 5.20435 1 6V18C1 18.7956 1.31607 19.5587 1.87868 20.1213C2.44129 20.6839 3.20435 21 4 21H20C20.7956 21 21.5587 20.6839 22.1213 20.1213C22.6839 19.5587 23 18.7956 23 18V6C23 5.20435 22.6839 4.44129 22.1213 3.87868C21.5587 3.31607 20.7956 3 20 3ZM19.672 15.62C19.7735 15.707 19.8566 15.8136 19.9162 15.9332C19.9758 16.0529 20.0109 16.1834 20.0192 16.3168C20.0275 16.4503 20.009 16.5841 19.9648 16.7103C19.9205 16.8365 19.8514 16.9525 19.7614 17.0515C19.6715 17.1505 19.5627 17.2304 19.4414 17.2866C19.32 17.3428 19.1886 17.3741 19.055 17.3786C18.9213 17.3832 18.7881 17.3608 18.6633 17.313C18.5384 17.2651 18.4244 17.1927 18.328 17.1L14.185 13.34C13.538 13.7716 12.7777 14.002 12 14.002C11.2223 14.002 10.462 13.7716 9.815 13.34L5.672 17.1C5.4744 17.2694 5.21854 17.3551 4.95878 17.3389C4.69902 17.3226 4.45582 17.2057 4.28085 17.0131C4.10587 16.8204 4.01291 16.5671 4.02172 16.307C4.03052 16.0468 4.1404 15.8004 4.328 15.62L8.284 12.029L4.327 8.43C4.22982 8.34162 4.151 8.23497 4.09504 8.11613C4.03908 7.99728 4.00708 7.86859 4.00086 7.73738C3.98829 7.47239 4.08151 7.21326 4.26 7.017C4.43849 6.82074 4.68763 6.70342 4.95262 6.69086C5.21761 6.67829 5.47674 6.77151 5.673 6.95L10.652 11.479C11.0212 11.8152 11.5026 12.0016 12.002 12.0016C12.5014 12.0016 12.9828 11.8152 13.352 11.479L18.327 6.95C18.5233 6.77151 18.7824 6.67829 19.0474 6.69086C19.3124 6.70342 19.5615 6.82074 19.74 7.017C19.9185 7.21326 20.0117 7.47239 19.9991 7.73738C19.9866 8.00237 19.8693 8.25151 19.673 8.43L15.717 12.03L19.672 15.62Z" fill="#6A6F74"/>
                        </svg>
                       <span>My Messages</span>&nbsp;&nbsp;&nbsp;
                                    @if($unreadChatCount > 0)
                                        <span class="badge bg-danger ms-auto text-white" style="display:none">{{ $unreadChatCount }}</span>
                                    @endif
                      </a></li>
                    <li><a href="{{ route('profile.settings','#AccountSettings') }}" class="pro-links {{ request()->routeIs('profile.settings') ? 'active' : '' }}"><svg width="22" height="24" viewBox="0 0 22 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M21.0107 14.4L19.8329 13.7C18.557 12.9 18.557 11 19.8329 10.2L21.0107 9.5C21.9921 8.9 22.2865 7.7 21.6977 6.8L20.7162 5.1C20.1273 4.1 18.9496 3.8 18.0663 4.4L16.8885 5.1C15.6127 5.9 13.9442 4.9 13.9442 3.4V2C13.9442 0.9 13.0609 0 11.9813 0H10.0184C8.93876 0 8.05545 0.9 8.05545 2V3.3C8.05545 4.8 6.38698 5.8 5.11109 5L3.93334 4.4C2.95189 3.8 1.77414 4.2 1.28341 5.1L0.30196 6.8C-0.188767 7.8 0.105669 9 0.988978 9.6L2.16672 10.3C3.44262 11 3.44262 13 2.16672 13.7L0.988978 14.4C0.00752359 15 -0.286913 16.2 0.30196 17.1L1.28341 18.8C1.87229 19.8 3.05003 20.1 3.93334 19.5L5.11109 18.9C6.38698 18.1 8.05545 19.1 8.05545 20.6V22C8.05545 23.1 8.93876 24 10.0184 24H11.9813C13.0609 24 13.9442 23.1 13.9442 22V20.7C13.9442 19.2 15.6127 18.2 16.8885 19L18.0663 19.7C19.0477 20.3 20.2255 19.9 20.7162 19L21.6977 17.3C22.1884 16.2 21.894 15 21.0107 14.4ZM10.9998 16C8.84062 16 7.074 14.2 7.074 12C7.074 9.8 8.84062 8 10.9998 8C13.159 8 14.9256 9.8 14.9256 12C14.9256 14.2 13.159 16 10.9998 16Z" fill="#6A6F74"/>
                        </svg> Settings</a></li>
                    <li>
                      <form id="logoutForm" method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="button" class="pro-links btn-link" id="logoutBtn">
                          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M16.4456 13.1216C16.2302 13.1039 16.0166 13.1723 15.8516 13.3118C15.6866 13.4514 15.5837 13.6507 15.5655 13.866C15.4933 14.7173 15.4039 15.4578 15.3258 15.847V15.8517C15.0951 17.0415 13.968 18.2176 12.8121 18.4762C12.7121 18.4968 12.6115 18.5165 12.5115 18.5354C12.5974 17.4303 12.6614 16.0171 12.6528 14.9274C12.6657 13.2625 12.5063 10.699 12.365 9.67887C12.1111 7.80051 10.9561 5.71727 9.67825 4.83199L9.67051 4.82684C8.78203 4.22661 7.82982 3.72659 6.83129 3.33592C6.65317 3.26691 6.47721 3.20248 6.30339 3.14263C7.25129 2.99608 8.20924 2.92427 9.16839 2.92787C10.4742 2.92787 11.6661 3.04685 12.8121 3.28481C13.968 3.54253 15.0951 4.71946 15.3258 5.90927V5.91399C15.4035 6.30057 15.4929 7.04109 15.565 7.88985C15.5834 8.10556 15.6866 8.30515 15.8521 8.44471C16.0176 8.58427 16.2318 8.65236 16.4475 8.63402C16.6632 8.61568 16.8628 8.5124 17.0024 8.34691C17.1419 8.18141 17.21 7.96725 17.1917 7.75154C17.1135 6.83277 17.0173 6.04715 16.9271 5.59528C16.571 3.76503 14.9512 2.08598 13.1592 1.68909L13.1489 1.68694C11.8895 1.42578 10.588 1.29778 9.16538 1.2952C7.74276 1.29263 6.44642 1.42406 5.18703 1.68737L5.17629 1.68952C4.3765 1.86691 3.61149 2.29989 2.9775 2.88706C2.80616 3.01626 2.65639 3.17182 2.53379 3.34795C1.97368 4.0017 1.56691 4.78389 1.40884 5.59528C1.21082 6.58793 0.987035 9.16214 1.00422 10.8803C0.994767 11.8021 1.05447 12.9704 1.14296 14.0086C1.19021 14.6744 1.2439 15.2542 1.29544 15.6262C1.5493 17.5046 2.70432 19.5878 3.98261 20.4731L3.98991 20.4782C4.87861 21.0784 5.83095 21.5784 6.82957 21.9692C7.84069 22.3617 8.76849 22.5997 9.66665 22.6968H9.67438C10.8814 22.8046 11.9784 21.757 12.3104 20.2252C12.5914 20.1806 12.8698 20.1297 13.1459 20.0728L13.1562 20.0706C14.9486 19.6737 16.568 17.9947 16.9241 16.1644C17.0143 15.7117 17.1109 14.9243 17.1891 14.0034C17.2073 13.7879 17.1392 13.574 16.9998 13.4087C16.8604 13.2433 16.6611 13.1401 16.4456 13.1216Z" fill="#6A6F74"/>
                            <path d="M22.9997 10.8791C22.9997 10.8521 22.9997 10.825 22.9954 10.7979C22.9956 10.7962 22.9956 10.7945 22.9954 10.7928C22.9928 10.7674 22.9889 10.7422 22.9838 10.7172V10.7116C22.9515 10.5565 22.8747 10.4142 22.7626 10.3022L19.8417 7.37883C19.6887 7.22573 19.4811 7.13969 19.2646 7.13965C19.0481 7.13961 18.8404 7.22557 18.6873 7.37862C18.5342 7.53167 18.4482 7.73928 18.4482 7.95576C18.4481 8.17225 18.5341 8.37989 18.6871 8.533L20.2193 10.0647H14.5851C14.3686 10.0647 14.161 10.1507 14.008 10.3038C13.8549 10.4568 13.769 10.6644 13.769 10.8808C13.769 11.0973 13.8549 11.3049 14.008 11.4579C14.161 11.611 14.3686 11.6969 14.5851 11.6969H20.212L18.6811 13.2278C18.5286 13.381 18.443 13.5884 18.4433 13.8045C18.4435 14.0207 18.5295 14.2279 18.6823 14.3808C18.8352 14.5336 19.0424 14.6196 19.2585 14.6198C19.4747 14.62 19.6821 14.5345 19.8353 14.382L22.7428 11.4745C22.8482 11.3757 22.9254 11.2505 22.9662 11.1119C22.9735 11.0872 22.9796 11.0621 22.9842 11.0368C22.9844 11.0332 22.9844 11.0296 22.9842 11.026C22.9881 11.0041 22.9919 10.9831 22.9941 10.9599C22.9962 10.9367 22.9941 10.9264 22.9941 10.91V10.8817L22.9997 10.8791Z" fill="#6A6F74"/>
                          </svg> Logout
                        </button>
                      </form>
                    </li>
                </ul>
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
            <div class="profile-right">
              <div class="mypro-content" id="profileContent">
                <div class="top-head">  
                  <h2>My Profile</h2>
                  <a href="#" class="edit-profile" id="editProfileBtn"><img src="images/edit-icon.svg" alt="brand-logo"></a>
                </div>
                
                @php
                $incompleteFields = [];
                if(empty($user->name)) $incompleteFields[] = 'Full Name';
                if(empty($user->mobile_number)) $incompleteFields[] = 'Contact Number';
                if(empty($user->gender)) $incompleteFields[] = 'Gender';
                if(empty($user->dob)) $incompleteFields[] = 'Date of Birth';
                $profileComplete = empty($incompleteFields);
                @endphp

                @if(!$profileComplete)
                <div class="alert alert-warning profile-incomplete-alert" role="alert">
                  <strong>Your profile is incomplete!</strong> Please complete the following information before continuing:
                  <ul>
                    @foreach($incompleteFields as $field)
                      <li>{{ $field }}</li>
                    @endforeach
                  </ul>
                </div>
                @endif
                
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                  {{ session('success') }}
                </div>
                @endif
                
                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  {{ session('error') }}
                </div>
                @endif
                
                <!-- Add validation error summary -->
                @if ($errors->any())
                <div class="alert alert-danger profile-validation-errors" role="alert">
                  <strong>Your profile update failed!</strong> Please fix the following issues:
                  <ul>
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
                @endif
                
                <form class="login-form profile-form" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="profileForm">
                  @csrf
                  @method('PUT')
                  <div class="row">
                    <div class="col-sm-12">
                      <label>Full Name <span style="color:red">*</span></label>
                      <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required readonly autocomplete="off">
                      @error('name')
                        <span class="text-danger">{{ $message }}</span>
                      @enderror
                    </div>
                    <div class="col-sm-12">
                      <label>University Email <span style="color:red">*</span></label>
                      <input type="email" class="form-control" value="{{ $user->email }}" readonly>
                    </div>
                    <div class="col-sm-12">
                      <label>Contact Number <span style="color:red">*</span></label>
                      <div class="phone-input-container">
                        <input type="tel" id="phone" name="mobile_number" class="form-control" 
                          pattern="[0-9]*" inputmode="numeric" 
                          oninput="this.value = this.value.replace(/[^0-9]/g, '')" 
                          value="{{ old('mobile_number', $user->mobile_number) }}" 
                          maxlength="13" 
                          readonly>
                        <input type="hidden" id="country_code" name="country_code" value="{{ old('country_code', $user->country_code) }}">
                        <input type="hidden" id="country_iso" name="country_iso" value="{{ old('country_iso', $user->country_iso ?? '') }}">
                      </div>
                      <span class="text-danger phone-error" style="display:none;">Phone number should not exceed 13 digits</span>
                      @error('mobile_number')
                        <span class="text-danger">{{ $message }}</span>
                      @enderror
                    </div>

                    
                    <style>
                      .phone-input-container .iti {
                        width: 100%;
                      }
                      .iti__flag-container {
                        z-index: 99;
                        opacity: 1 !important; /* Always show flag */
                      }
                      /* Make sure flags are visible even when disabled */
                      .iti.iti--disabled .iti__flag-container {
                        opacity: 1 !important;
                        pointer-events: none;
                      }
                      .iti.iti--disabled .iti__selected-flag {
                        background-color: transparent !important;
                        pointer-events: none;
                      }
                    </style>



                    <div class="col-sm-12">
                      <label>Gender <span style="color:red">*</span></label>
                      <select class="form-control" name="gender" disabled>
                        <option value="">Select Gender</option>
                        <option value="Male" {{ old('gender', $user->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('gender', $user->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Other" {{ old('gender', $user->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                      </select>
                      @error('gender')
                        <span class="text-danger">{{ $message }}</span>
                      @enderror
                    </div>
                    <div class="col-sm-12">
                      <label>Date of Birth <span style="color:red">*</span></label>
                      <input type="date" name="dob" id="dob" class="form-control" value="{{ old('dob', $user->dob) }}" readonly>
                      @error('dob')
                        <span class="text-danger">{{ $message }}</span>
                      @enderror
                    </div>
                    <div class="col-sm-12 mt-4">
                      <button type="submit" class="btn btn-primary" id="updateProfileBtn" disabled>Update Profile</button>
                      <button type="button" class="btn btn-secondary" id="cancelEditBtn" style="display:none;">Cancel</button>
                    </div>
                  </div>
                </form>
              </div>
              {{-- <div class="current-sale">
                <div class="sell-more">
                  <h2>Current Selling</h2>
                  <a href="{{ route('seller.products.create') }}">Sell More Product(s)</a>
                </div>
                <ul class="product-list">
                  @forelse($userProducts as $product) 
                    <li class="product-item addmore-optn" data-product-url="{{ route('products.show', ['product' => $product->id]) }}">
                      <figure>
                        @php
                        $photos = $product->photos;
                        if (is_string($photos)) {
                            $photos = json_decode($photos, true);
                        }
                        $photoSrc = (is_array($photos) && !empty($photos)) ? $photos[0] : asset('images/item-img5.png');
                      @endphp
                        <img src="{{ $photoSrc }}" alt="{{ $product->name }}">
                      </figure>
                      <label>{{ $product->condition }}</label>
                      <a href="{{route('seller.products.edit',$product)}}" class="edit-profile" id="editProfileBtn"><img src="images/edit-icon.svg" alt="brand-logo"></a>
                      <form class="delete-mode" action="{{ route('seller.products.destroy', $product) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn-link p-0 border-0 bg-transparent" data-toggle="modal" data-target="#deleteModal{{ $product->id }}">
                            <img src="{{ asset('images/dlt-icon1.svg') }}" alt="delete-icon">
                        </button>

                        <div class="modal fade" id="deleteModal{{ $product->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $product->id }}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel{{ $product->id }}">Delete Product</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to delete this product?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="window.location.href='{{ route('seller.products') }}'">Cancel</button>
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                      <div class="item-content">
                        <a href="{{ route('products.show', ['product' => $product->id]) }}" class="product-name">{{ $product->name }}</a>
                        <p><img src="{{ asset('images/label-icon.svg') }}" alt="category"> {{ $product->category->name }}</p>
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
                      </div>
                    </li>
                  @empty
                    <li class="no-products">
                      <p>You don't have any products listed for sale yet.</p>
                    @endforelse
                </ul>
              </div> --}}
            </div>
        </div>
      </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Calculate the max date (18 years ago)
        const today = new Date();
        const minAgeDate = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());
        const maxDateStr = minAgeDate.toISOString().split('T')[0];
        
        // Set the max attribute on the DOB field
        const dobField = document.getElementById('dob');
        if (dobField) {
            dobField.setAttribute('max', maxDateStr);
            
            // Add validation on change
            dobField.addEventListener('change', function() {
                const selectedDate = new Date(this.value);
                if (selectedDate > minAgeDate) {
                    alert('You must be at least 18 years old.');
                    this.value = maxDateStr;
                }
            });
        }
        
        // Make product items clickable
        const productItems = document.querySelectorAll('.product-item');
        productItems.forEach(item => {
            item.style.cursor = 'pointer';
            item.addEventListener('click', function(e) {
                // Don't redirect if clicking on an actual link or button
                if (e.target.tagName === 'A' || e.target.tagName === 'BUTTON') {
                    return;
                }
                const url = this.getAttribute('data-product-url');
                if (url) {
                    window.location.href = url;
                }
            });
        });

        // Profile Edit Functionality - MODIFIED TO PREVENT POPUP
        const editProfileBtn = document.getElementById('editProfileBtn');
        const updateProfileBtn = document.getElementById('updateProfileBtn');
        const cancelEditBtn = document.getElementById('cancelEditBtn');
        
        if (editProfileBtn) {
            editProfileBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation(); // Prevent event bubbling
                
                // Always enable edit mode directly without popup
                enableFormFields();
                
                // Show buttons
                updateProfileBtn.style.display = 'inline-block';
                if (cancelEditBtn) cancelEditBtn.style.display = 'inline-block';
            });
        }
        
        // Function to enable form fields - extracting this for reuse
        function enableFormFields() {
            // Target specific fields directly by name
            const nameField = document.querySelector('input[name="name"]');
            const mobileField = document.querySelector('input[name="mobile_number"]');
            const dobField = document.querySelector('input[name="dob"]');
            const genderField = document.querySelector('select[name="gender"]');
            
            // Enable each field individually
            if (nameField) nameField.removeAttribute('readonly');
            if (mobileField) mobileField.removeAttribute('readonly');
            if (dobField) dobField.removeAttribute('readonly'); 
            if (genderField) genderField.disabled = false;
            
            // Focus on name field and position cursor at the end
            if (nameField) {
                nameField.focus();
                // Set cursor position to the end of the text
                const length = nameField.value.length;
                nameField.setSelectionRange(length, length);
            }
            
            // If using intlTelInput for phone, enable it
            if (window.intlTelInputGlobals) {
                const input = document.querySelector("#phone");
                if (input) {
                    const iti = window.intlTelInputGlobals.getInstance(input);
                    if (iti) iti.enable();
                }
            }
        }
        
        // Cancel edit functionality
        if (cancelEditBtn) {
            cancelEditBtn.addEventListener('click', function() {
                // Reset form to original values and disable editing
                document.getElementById('profileForm').reset();
                disableFormFields();
                updateProfileBtn.style.display = 'none';
                cancelEditBtn.style.display = 'none';
            });
        }
        
        function disableFormFields() {
            const nameField = document.querySelector('input[name="name"]');
            const mobileField = document.querySelector('input[name="mobile_number"]');
            const dobField = document.querySelector('input[name="dob"]');
            const genderField = document.querySelector('select[name="gender"]');
            
            if (nameField) nameField.setAttribute('readonly', true);
            if (mobileField) mobileField.setAttribute('readonly', true);
            if (dobField) dobField.setAttribute('readonly', true);
            if (genderField) genderField.disabled = true;
            
            // If using intlTelInput, disable it
            if (window.intlTelInputGlobals) {
                const input = document.querySelector("#phone");
                if (input) {
                    const iti = window.intlTelInputGlobals.getInstance(input);
                    if (iti) iti.disable();
                }
            }
        }
    });
    
    document.addEventListener('DOMContentLoaded', function() {
        // Add click event to all product items
        const productItems = document.querySelectorAll('.product-item');
        productItems.forEach(item => {
            item.addEventListener('click', function(event) {
                // Don't navigate if clicking on buttons or forms
                if (event.target.closest('.edit-profile') || 
                    event.target.closest('.delete-mode') || 
                    event.target.closest('form')) {
                    event.stopPropagation();
                    return;
                }
                
                // Get the URL from data attribute and navigate to it
                const productUrl = this.getAttribute('data-product-url');
                if (productUrl) {
                    window.location.href = productUrl;
                }
            });
        });
        
        // Ensure modals work correctly
        $('.delete-mode').on('click', function(e) {
            e.stopPropagation();
            const target = $(this).data('target');
            $(target).modal('show');
        });
    });
    </script>
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            Are you sure you want to logout?
          </div>
          <div class="modal-footer">
            <a href="{{ route('profile.show') }}" class="btn btn-primary">Cancel</a>
            <button type="button" class="btn btn-secondary" id="confirmLogout">Logout</button>
          </div>
        </div>
      </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Define a simpler approach for modal handling
      const showModal = function() {
        const modal = document.getElementById('logoutModal');
        modal.style.display = 'block';
        modal.classList.add('show');
        document.body.classList.add('modal-open');
        
        // Create backdrop if it doesn't exist
        if (document.getElementsByClassName('modal-backdrop').length === 0) {
          const backdrop = document.createElement('div');
          backdrop.className = 'modal-backdrop fade show';
          document.body.appendChild(backdrop);
        }
      };
      
      // Logout button click handler
      document.getElementById('logoutBtn').addEventListener('click', function(e) {
        e.preventDefault();
        
        // Try using Bootstrap's modal API first
        try {
          if (typeof bootstrap !== 'undefined') {
            var logoutModal = new bootstrap.Modal(document.getElementById('logoutModal'));
            logoutModal.show();
          } else {
            // Fallback to our custom implementation
            showModal();
          }
        } catch(e) {
          console.error('Modal error:', e);
          // Fallback to our custom implementation
          showModal();
        }
      });

      // Confirm logout handler
      document.getElementById('confirmLogout').addEventListener('click', function() {
        document.getElementById('logoutForm').submit();
      });
    });

    document.addEventListener('DOMContentLoaded', function() {
    // ...existing code...

    // Replace the existing validatePhone function to completely remove format validation
    HTMLInputElement.prototype.validatePhone = function() {
      const input = document.querySelector("#phone");
      const countryCodeInput = document.querySelector("#country_code");
      const errorElement = document.querySelector('.phone-error');
      
      // Always hide error message
      if (errorElement) {
        errorElement.style.display = 'none';
      }
      
      // Save the country code if the plugin is initialized
      if (window.intlTelInputGlobals) {
        const iti = window.intlTelInputGlobals.getInstance(input);
        if (iti) {
          const selectedCountryData = iti.getSelectedCountryData();
          if (selectedCountryData && selectedCountryData.dialCode) {
            countryCodeInput.value = selectedCountryData.dialCode;
          }
        }
      }
      
      // Always return true - no validation at all
      return true;
    };
    
    // Form submission handler that just continues without validation
    const form = document.querySelector('.profile-form');
    if (form) {
      form.addEventListener('submit', function(e) {
        // No validation, just let the form submit
        const phoneInput = this.querySelector('input[name="mobile_number"]');
        if (phoneInput) {
          phoneInput.validatePhone();
        }
      });
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
                          },
                          // Add custom formatting to remove spaces
                          customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
                            return selectedCountryPlaceholder.replace(/[- ]/g, '');
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
                          
                          // Remove any spaces from the phone number
                          removeSpacesFromPhoneInput();
                        }
                        
                        // Function to remove spaces from phone input
                        function removeSpacesFromPhoneInput() {
                          if (input.value) {
                            input.value = input.value.replace(/\s+/g, '');
                          }
                        }
                        
                        // Listen for country changes
                        input.addEventListener('countrychange', function() {
                          updateCountryData();
                          // Wait a bit for the input to update and then remove spaces again
                          setTimeout(removeSpacesFromPhoneInput, 100);
                        });
                        
                        // Also remove spaces when input value changes
                        input.addEventListener('input', removeSpacesFromPhoneInput);
                        
                        // Override the default blur behavior to prevent automatic formatting
                        input.addEventListener('blur', function(e) {
                          // Remove spaces on blur as well
                          removeSpacesFromPhoneInput();
                          // Prevent default intl-tel-input formatting
                          e.preventDefault();
                          e.stopPropagation();
                        }, true);
                        
                        // Update validation on edit mode toggle
                        document.getElementById('editProfileBtn').addEventListener('click', function() {
                          if (input.hasAttribute('readonly')) {
                            input.removeAttribute('readonly');
                            iti.enable(); // Enable the telephone input
                            // Remove any spaces that might have been added when enabling
                            setTimeout(removeSpacesFromPhoneInput, 100);
                          }
                        });
                        
                        // Ensure country data is captured before form submission
                        document.querySelector('.profile-form').addEventListener('submit', function(e) {
                          updateCountryData();
                          removeSpacesFromPhoneInput();
                        });

                        // Set initial country data and handle UI after component is fully initialized
                        setTimeout(function() {
                          updateCountryData();
                          removeSpacesFromPhoneInput(); // Remove spaces on initial load
                          
                          // Make sure the flag is visible even in readonly mode
                          if (input.hasAttribute('readonly')) {
                            // Force enable temporarily to ensure flag is rendered
                            iti.enable();
                            
                            // Then disable but keep flag visible
                            setTimeout(() => {
                              iti.disable();
                              removeSpacesFromPhoneInput(); // Remove spaces again after disabling
                              
                              // Override the CSS to make flags visible in disabled state
                              const flagContainer = document.querySelector('.iti__flag-container');
                              if (flagContainer) {
                                flagContainer.style.opacity = '1';
                                flagContainer.style.pointerEvents = 'none';
                              }
                            }, 100);
                          }
                        }, 500);
                        
                        // Final cleanup to ensure we remove any spaces that might have been added
                        input.value = input.value.replace(/\s+/g, '');
                      });
                    </script>

@php
// Add this helper function to handle objects safely
function safeOutput($value) {
    if (is_object($value)) {
        if (method_exists($value, '__toString')) {
            return (string)$value;
        } else {
            return json_encode($value);
        }
    } elseif (is_array($value)) {
        return json_encode($value);
    } else {
        return $value;
    }
}
@endphp

<!-- Replace instances where objects might be directly output with the safe approach -->
<!-- For example, change lines like these: -->
{{-- <!-- {{ $someObject }} --> --}}
<!-- To this: -->
{{-- <!-- {{ safeOutput($someObject) }} --> --}}

<!-- Look for any direct object output in the profile view -->
<!-- Common places to check would be user metadata, attributes, or settings -->

<!-- If you're outputting user data or university data, use the safe method -->
<!-- For example: -->
<!-- Replace {{ $user->university }} with {{ safeOutput($user->university) }} if university might be an object -->

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // ...existing code...
        
        // Profile completeness check
        const profileComplete = {{ $profileComplete ? 'true' : 'false' }};
        
        // If profile is incomplete, prevent navigation to other modules
        if (!profileComplete) {
            // Add event listeners to all navigation links except profile-related ones
            document.querySelectorAll('.pro-links').forEach(link => {
                if (!link.href || link.href.includes('profile') || link.href.includes('settings')) {
                    return; // Allow navigation to profile/settings pages
                }
                
                link.addEventListener('click', function(e) {
                    // Check if this is the edit button click - EXCLUDE EDIT BUTTON FROM RESTRICTIONS
                    if (this.id === 'editProfileBtn') {
                        return true; // Allow edit button to work normally
                    }
                    
                    e.preventDefault();
                    alert('Please complete your profile information before accessing other features.');
                    
                    // Highlight incomplete fields
                    const alertBox = document.querySelector('.profile-incomplete-alert');
                    if (alertBox) {
                        alertBox.scrollIntoView({ behavior: 'smooth' });
                        alertBox.classList.add('highlight-alert');
                        setTimeout(() => alertBox.classList.remove('highlight-alert'), 1500);
                    }
                    
                    // Auto-open edit mode 
                    const editBtn = document.getElementById('editProfileBtn');
                    if (editBtn) editBtn.click();
                    
                    return false;
                });
            });
            
            // Auto-focus on first empty required field but DON'T auto-trigger edit button click
            window.setTimeout(function() {
                // Focus on first incomplete field if already in edit mode
                if (!document.querySelector('input[name="name"]').hasAttribute('readonly')) {
                    @foreach($incompleteFields as $field)
                        @if($field == 'Full Name')
                            document.querySelector('input[name="name"]').focus();
                            break;
                        @elseif($field == 'Contact Number')
                            document.querySelector('input[name="mobile_number"]').focus();
                            break;
                        @elseif($field == 'Gender')
                            document.querySelector('select[name="gender"]').focus();
                            break;
                        @elseif($field == 'Date of Birth')
                            document.querySelector('input[name="dob"]').focus();
                            break;
                        @endif
                    @endforeach
                }
            }, 500);
        }
    });
    
    // Add CSS for highlighting
    document.head.insertAdjacentHTML('beforeend', `
        <style>
            .profile-incomplete-alert {
                border-left: 4px solid #ffc107;
                margin-bottom: 20px;
            }
            .profile-incomplete-alert ul {
                margin-top: 10px;
                margin-bottom: 0;
            }
            .highlight-alert {
                animation: pulse-border 1.5s;
            }
            @keyframes pulse-border {
                0% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.7); }
                70% { box-shadow: 0 0 0 10px rgba(255, 193, 7, 0); }
                100% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0); }
            }
        </style>
    `);
    </script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    // ...existing code...
    
    // Profile completeness check
    const profileComplete = {{ $profileComplete ? 'true' : 'false' }};
    
    // If profile is incomplete, prevent navigation to other modules
    if (!profileComplete) {
        // Add event listeners to all navigation links
        document.querySelectorAll('a').forEach(link => {
            // Skip links without href
            if (!link.href) return;
            
            // Skip the edit button and its container
            if (link.id === 'editProfileBtn' || link.closest('#editProfileBtn')) {
                return;
            }
            
            // Only allow specific profile pages, settings, and logout
            const allowedPaths = [
                '/profile/show',
                '/profile/edit',
                '/profile/update', 
                '/logout'
            ];
            
            // Check if this link is allowed
            let isAllowed = false;
            for (const path of allowedPaths) {
                if (link.href.includes(path)) {
                    isAllowed = true;
                    break;
                }
            }
            
            // If route is not allowed, prevent navigation
            if (!isAllowed) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Show alert instead of modal
                    // alert('Please complete your profile information before accessing other features.');
                    
                    // Highlight incomplete fields
                    const alertBox = document.querySelector('.profile-incomplete-alert');
                    if (alertBox) {
                        alertBox.scrollIntoView({ behavior: 'smooth' });
                        alertBox.classList.add('highlight-alert');
                        setTimeout(() => alertBox.classList.remove('highlight-alert'), 1500);
                    }
                    
                    return false;
                });
            }
        });
        
        // Remove auto-trigger of edit mode on page load
        // The edit button should only be triggered by user action
    }
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ...existing code...
    
    // Profile completeness check
    const profileComplete = {{ $profileComplete ? 'true' : 'false' }};
    
    // Create and add modal HTML if it doesn't exist
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
                    @foreach($incompleteFields as $field)
                      <li>{{ $field }}</li>
                    @endforeach
                  </ul>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-primary" id="profileModalOkBtn" data-dismiss="modal">Ok</button>
                </div>
              </div>
            </div>
          </div>
        `;
        document.body.insertAdjacentHTML('beforeend', modalHTML);
    }

    // Function to show the incomplete profile modal
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
        
        // Highlight incomplete fields alert box if it exists
        const alertBox = document.querySelector('.profile-incomplete-alert');
        if (alertBox) {
            alertBox.scrollIntoView({ behavior: 'smooth' });
            alertBox.classList.add('highlight-alert');
            setTimeout(() => alertBox.classList.remove('highlight-alert'), 1500);
        }
    }
    
    // Add event handler for the Ok button
    document.addEventListener('click', function(e) {
        if (e.target && e.target.id === 'profileModalOkBtn') {
            // Auto-open edit mode after modal is closed
            setTimeout(function() {
                const editBtn = document.getElementById('editProfileBtn');
                if (editBtn) editBtn.click();
            }, 300);
        }
    });
    
    // If profile is incomplete, prevent navigation to other modules
    if (!profileComplete) {
        // Add event listeners to all navigation links except profile-related ones
        document.querySelectorAll('.pro-links').forEach(link => {
            if (!link.href || link.href.includes('profile') || link.href.includes('settings')) {
                return; // Allow navigation to profile/settings pages
            }
            
            if (link.id === 'editProfileBtn') {
                return; // Allow edit button to work normally
            }
            
            link.addEventListener('click', function(e) {
                e.preventDefault();
                showIncompleteProfileModal();
                return false;
            });
        });
        
        // Add listeners to ALL links to prevent navigation
        document.querySelectorAll('a').forEach(link => {
            // Skip links without href
            if (!link.href) return;
            
            // Skip the edit button and its container
            if (link.id === 'editProfileBtn' || link.closest('#editProfileBtn')) {
                return;
            }
            
            // Only allow specific profile pages, settings, and logout
            const allowedPaths = [
                '/profile/show',
                '/profile/edit',
                '/profile/update', 
                '/logout'
            ];
            
            // Check if this link is allowed
            let isAllowed = false;
            for (const path of allowedPaths) {
                if (link.href.includes(path)) {
                    isAllowed = true;
                    break;
                }
            }
            
            // If route is not allowed, prevent navigation
            if (!isAllowed) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Show modal instead of alert
                    showIncompleteProfileModal();
                    
                    return false;
                });
            }
        });
    }
});
</script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const input = document.querySelector("#phone");
    const countryCodeInput = document.querySelector("#country_code");
    const countryIsoInput = document.querySelector("#country_iso");
    let iti;
    
    // Common dial code to country ISO mappings
    const dialCodeToCountry = {
      // ...existing country code mappings...
    };
    
    // Robust phone input cleaning logic
    function cleanPhoneInput() {
      if (!input || !iti) return;
      let val = input.value || '';
      // Remove all spaces, dashes, parentheses
      val = val.replace(/[\s\-\(\)]/g, '');
      // Remove all leading country codes (with or without '+')
      const selectedCountry = iti.getSelectedCountryData();
      if (selectedCountry && selectedCountry.dialCode) {
        const dialCode = selectedCountry.dialCode;
        // Remove all repeated dial codes at the start (with or without '+')
        let regex = new RegExp(`^(\+?${dialCode})+`);
        while (regex.test(val)) {
          val = val.replace(regex, '');
        }
      }
      // Remove any leading '+' left
      val = val.replace(/^\+/, '');
      input.value = val;
    }
    
    // Attach cleaning to all relevant events
    ['input', 'change', 'keyup', 'blur', 'focus', 'paste'].forEach(eventName => {
      input.addEventListener(eventName, function() {
        setTimeout(cleanPhoneInput, 0);
      });
    });
    input.addEventListener('countrychange', function() {
      updateCountryData();
      setTimeout(cleanPhoneInput, 0);
    });
    
    // Clean before form submit
    const profileForm = document.querySelector('.profile-form');
    if (profileForm) {
      profileForm.addEventListener('submit', function() {
        cleanPhoneInput();
      });
    }
    
    // Initial clean on load
    setTimeout(cleanPhoneInput, 500);
  });
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ...existing code...
    
    // Profile Edit Functionality - FIX TO ENABLE UPDATE BUTTON
    const editProfileBtn = document.getElementById('editProfileBtn');
    const updateProfileBtn = document.getElementById('updateProfileBtn');
    const cancelEditBtn = document.getElementById('cancelEditBtn');
    
    if (editProfileBtn) {
        editProfileBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation(); // Prevent event bubbling
            
            console.log("Edit profile button clicked"); // Debug
            
            // Always enable edit mode directly without popup
            enableFormFields();
            
            // Show and ENABLE buttons
            if (updateProfileBtn) {
                updateProfileBtn.disabled = false; // Explicitly enable the button
                updateProfileBtn.style.display = 'inline-block';
                updateProfileBtn.style.cssText = "display: inline-block !important; visibility: visible !important; opacity: 1 !important;";
                console.log("Update button enabled:", !updateProfileBtn.disabled);
            }
            
            if (cancelEditBtn) {
                cancelEditBtn.style.display = 'inline-block';
                cancelEditBtn.disabled = false;
            }
        });
    }
    
    // Function to enable form fields - UPDATED to also enable update button
    function enableFormFields() {
        // Target specific fields directly by name
        const nameField = document.querySelector('input[name="name"]');
        const mobileField = document.querySelector('input[name="mobile_number"]');
        const dobField = document.querySelector('input[name="dob"]');
        const genderField = document.querySelector('select[name="gender"]');
        const updateBtn = document.getElementById('updateProfileBtn');
        
        // Enable each field individually
        if (nameField) nameField.removeAttribute('readonly');
        if (mobileField) mobileField.removeAttribute('readonly');
        if (dobField) dobField.removeAttribute('readonly'); 
        if (genderField) genderField.disabled = false;
        
        // Explicitly enable the update button
        if (updateBtn) {
            updateBtn.disabled = false;
            updateBtn.classList.add('enabled-button');
        }
        
        // Focus on name field and position cursor at the end
        if (nameField) {
            nameField.focus();
            // Set cursor position to the end of the text
            const length = nameField.value.length;
            nameField.setSelectionRange(length, length);
        }
        
        // If using intlTelInput for phone, enable it
        if (window.intlTelInputGlobals) {
            const input = document.querySelector("#phone");
            if (input) {
                const iti = window.intlTelInputGlobals.getInstance(input);
                if (iti) iti.enable();
            }
        }
    }
    
    // Cancel edit functionality - UPDATED to disable the update button again
    if (cancelEditBtn) {
        cancelEditBtn.addEventListener('click', function() {
            // Reset form to original values and disable editing
            document.getElementById('profileForm').reset();
            disableFormFields();
            updateProfileBtn.style.display = 'inline-block';
            updateProfileBtn.disabled = true; // Re-disable the button
            cancelEditBtn.style.display = 'none';
        });
    }
    
    function disableFormFields() {
        const nameField = document.querySelector('input[name="name"]');
        const mobileField = document.querySelector('input[name="mobile_number"]');
        const dobField = document.querySelector('input[name="dob"]');
        const genderField = document.querySelector('select[name="gender"]');
        
        if (nameField) nameField.setAttribute('readonly', true);
        if (mobileField) mobileField.setAttribute('readonly', true);
        if (dobField) dobField.setAttribute('readonly', true);
        if (genderField) genderField.disabled = true;
        
        // If using intlTelInput, disable it
        if (window.intlTelInputGlobals) {
            const input = document.querySelector("#phone");
            if (input) {
                const iti = window.intlTelInputGlobals.getInstance(input);
                if (iti) iti.disable();
            }
        }
    }
});
</script>
<script>
// --- Smooth scroll to top function (30s duration, with callback) ---
function smoothScrollToTopSlow(duration = 30000, callback) {
    const start = window.scrollY || window.pageYOffset;
    const startTime = performance.now();
    function scrollStep(now) {
        const elapsed = now - startTime;
        const progress = Math.min(elapsed / duration, 25);
        window.scrollTo({ top: start * (25 - progress), behavior: 'auto' });
        if (progress < 25) {

            requestAnimationFrame(scrollStep);
        } else if (typeof callback === 'function') {
            callback();
        }
    }
    requestAnimationFrame(scrollStep);
}

// --- Apply to all specified elements for smooth scroll on click ---
['#profileContent', '.mypro-content', '.list-product', '.profileRight'].forEach(function(selector) {
    document.querySelectorAll(selector).forEach(function(el) {
        el.addEventListener('click', function(e) {
            // Prevent default if it's a link or form
            if (e.target.tagName === 'A' || e.target.closest('form')) return;
            e.preventDefault();
            smoothScrollToTopSlow(30000);
        });
    });
});
</script>
@endsection

