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
                                </a>
                        </li>
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
                    <figure class="tips-label"><img src="{{asset('images/safety-tips-img.png')}}" alt="google"></figure>
                </div>
            </div>
            <div class="profile-right">
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
                <div class="mypro-content">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <div class="top-head" id="list-product">
                        <h4>List a Product</h4>
                    </div>
                    <div class="">
                        <form class="login-form profile-form" action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
                            @csrf
                            
                            <div id="dynamicFields"></div>

                            <div class="mb-3">
                                <label class="form-label">Title <span style="color:red">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                                @error('name')
                                <div class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description <span style="color:red">*</span></label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description') }}</textarea>
                                @error('description')
                                <div class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </div>
                                @enderror
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Price $ <span class="text-danger" style="color:red">*</span></label>
                                        <input type="number" name="mrp" class="form-control @error('mrp') is-invalid @enderror" min="0" value="{{ old('mrp') }}">
                                        @error('mrp')
                                            <div class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Category <span class="text-danger" style="color:red">*</span></label>
                                    <select name="category_id" id="categorySelect" class="form-control @error('category_id') is-invalid @enderror">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" 
                                                    data-fields="{{ json_encode($category->required_fields ?? []) }}"
                                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Condition <span class="text-danger" style="color:red">*</span></label>
                                    <select name="condition" class="form-control @error('condition') is-invalid @enderror" >
                                        <option value="">Select Condition</option>
                                        <option value="New" {{ old('condition') == 'New' ? 'selected' : '' }}>New</option>
                                        <option value="Like New" {{ old('condition') == 'Like New' ? 'selected' : '' }}>Like New</option>
                                        <option value="Good" {{ old('condition') == 'Good' ? 'selected' : '' }}>Good</option>
                                        <option value="Fair" {{ old('condition') == 'Fair' ? 'selected' : '' }}>Fair</option>
                                        <option value="Poor" {{ old('condition') == 'Poor' ? 'selected' : '' }}>Poor</option>
                                    </select>
                                    @error('condition')
                                        <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>
                                {{-- <div class="mb-3 col-md-6">
                                    <label class="form-label">Meeting Location <span class="text-danger" style="color:red">*</span></label>
                                    <input type="text" name="location" class="form-control @error('location') is-invalid @enderror" value="{{ old('location') }}">
                                    @error('location')
                                        <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div> --}}
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Meeting Location <span class="text-danger" style="color:red">*</span></label>
                                    <input type="text" class="form-control" name="location" id="inputAddressvalue" placeholder="Type to search for a location"  required>
                                    <input type="hidden" id="lat" name="location_lat">
                                    <input type="hidden" id="long" name="location_long">
                                    <div id="searchmap" style="height: 200px; margin-top: 10px; border-radius: 8px;"></div>
                                    {{--    <small class="text-muted mt-1">Search for a meeting location or allow browser to detect your current location</small> --}}
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Payment methods <span style="color:red">*</span></label>
                                <select name="payment_method[]" class="form-control select2-multiple @error('payment_method') is-invalid @enderror" multiple>
                                    <option value="Cash" {{ is_array(old('payment_method')) && in_array('Cash', old('payment_method')) ? 'selected' : '' }}>Cash</option>
                                    <option value="Venmo" {{ is_array(old('payment_method')) && in_array('Venmo', old('payment_method')) ? 'selected' : '' }}>Venmo</option>
                                    <option value="Paypal" {{ is_array(old('payment_method')) && in_array('Paypal', old('payment_method')) ? 'selected' : '' }}>Paypal</option>
                                    <option value="Zelle" {{ is_array(old('payment_method')) && in_array('Zelle', old('payment_method')) ? 'selected' : '' }}>Zelle</option>
                                    <option value="Free" {{ is_array(old('payment_method')) && in_array('Free', old('payment_method')) ? 'selected' : '' }}>Free</option>
                                </select>
                                @error('payment_method')
                                <div class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Upload Images (Max 5) <span class="text-danger">*</span></label>
                                <div class="d-flex align-items-center mb-2">
                                    <button type="button" class="btn btn-outline-secondary" id="addImageBtn">
                                        <i class="fa fa-plus-circle"></i> Add Image
                                    </button>
                                    <input type="file" id="imageInput" accept="image/*" style="display: none">
                                    <div class="ms-2 text-muted small">
                                        <span id="imageCounter">0</span>/5 images selected
                                    </div>
                                </div>
                                <div id="imagePreviewContainer" class="row mt-2"></div>
                                @error('photos')
                                    <div class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                                @error('photos.*')
                                    <div class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>

                        

                            <button type="submit" class="btn btn-primary">Sell Product</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Category-specific fields
    const categorySelect = document.getElementById('categorySelect');
    const dynamicFields = document.getElementById('dynamicFields');

    categorySelect.addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        const fields = JSON.parse(option.dataset.fields || '[]');
        
        dynamicFields.innerHTML = fields.map(field => `
            <div class="mb-3">
                <label class="form-label">${field.label}</label>
                <input type="${field.type}" name="fields[${field.name}]" 
                       class="form-control" ${field.required ? 'required' : ''}>
            </div>
        `).join('');
    });

    // Image handling functionality
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('imageInput');
        const addImageBtn = document.getElementById('addImageBtn');
        const imagePreviewContainer = document.getElementById('imagePreviewContainer');
        const imageCounter = document.getElementById('imageCounter');
        const maxImages = 5;
        let currentImages = 0;
        
        // Function to update image counter based on actual elements
        function updateImageCounter() {
            // Count the actual number of preview elements
            const imageElements = imagePreviewContainer.querySelectorAll('[id^="preview_"]');
            currentImages = imageElements.length;
            imageCounter.textContent = currentImages;
            
            // Enable/disable add button based on count
            addImageBtn.disabled = (currentImages >= maxImages);
        }
        
        // Add button click triggers file input
        addImageBtn.addEventListener('click', function() {
            if (currentImages < maxImages) {
                imageInput.click();
            }
        });
        
        // Handle file selection
        imageInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                if (currentImages >= maxImages) {
                    alert('Maximum 5 images allowed');
                    return;
                }
                
                const file = this.files[0];
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const imageId = 'img_' + Date.now();
                    
                    // Create preview container
                    const previewCol = document.createElement('div');
                    previewCol.className = 'col-md-3 mb-2';
                    previewCol.id = 'preview_' + imageId;
                    
                    // Create preview card
                    const card = document.createElement('div');
                    card.className = 'card h-100';
                    
                    // Create image preview
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'card-img-top';
                    img.style.height = '120px';
                    img.style.objectFit = 'cover';
                    
                    // Create card body with remove button
                    const cardBody = document.createElement('div');
                    cardBody.className = 'card-body p-2';
                    
                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'btn btn-sm btn-danger w-100';
                    removeBtn.textContent = 'Remove';
                    removeBtn.dataset.id = imageId;
                    
                    removeBtn.addEventListener('click', function(e) {
                        // Stop event propagation
                        e.preventDefault();
                        e.stopPropagation();
                        
                        // Remove elements
                        const previewElement = document.getElementById('preview_' + this.dataset.id);
                        const fileElement = document.getElementById('file_' + this.dataset.id);
                        
                        if (previewElement) previewElement.remove();
                        if (fileElement) fileElement.remove();
                        
                        // Force synchronous DOM update and counter refresh
                        setTimeout(function() {
                            updateImageCounter();
                        }, 0);
                    });
                    
                    // Create hidden input for form submission
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'file';
                    hiddenInput.name = 'photos[]';
                    hiddenInput.id = 'file_' + imageId;
                    hiddenInput.style.display = 'none';
                    
                    // Create a new DataTransfer object and add the file
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    hiddenInput.files = dataTransfer.files;
                    
                    // Assemble the preview
                    cardBody.appendChild(removeBtn);
                    card.appendChild(img);
                    card.appendChild(cardBody);
                    previewCol.appendChild(card);
                    previewCol.appendChild(hiddenInput);
                    
                    imagePreviewContainer.appendChild(previewCol);
                    
                    // Update counter using the dedicated function
                    updateImageCounter();
                };
                
                reader.readAsDataURL(file);
                
                // Reset file input for next selection
                this.value = '';
            }
        });
        
        // Form validation
        document.getElementById('productForm').addEventListener('submit', function(e) {
            // Force a final counter update before validating
            updateImageCounter();
            
            // if (currentImages === 0) {
            //     e.preventDefault();
            //     alert('Please add at least one image');
            // }
        });
    });
   
    // Initialize map when Google Maps API is ready
    if (typeof google !== 'undefined') {
        initMap();
    } else {
        document.getElementById('map').innerHTML = '<div class="alert alert-warning">Google Maps could not be loaded. Please check your internet connection.</div>';
    }
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
          <a href="{{ route('seller.products.create') }}" class="btn btn-primary">Cancel</a>
          <button type="button" class="btn btn-secondary" id="confirmLogout">Logout</button>
        </div>
      </div>
    </div>
  </div>

<script>
  getLocation();

  function getLocation() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(showPosition, function() {
        // fallback if user denies location
        initMap(40.749933, -73.98633);
      });
    } else {
      initMap(40.749933, -73.98633);
    }
  }

  function showPosition(position) {
    initMap(position.coords.latitude, position.coords.longitude);
  }

  function initMap(latitude, longitude) {
    const map = new google.maps.Map(document.getElementById("searchmap"), {
      center: {
        lat: parseFloat(latitude),
        lng: parseFloat(longitude)
      },
      zoom: 13,
      mapTypeControl: false,
    });

    const input = document.getElementById('inputAddressvalue');
    const autocomplete = new google.maps.places.Autocomplete(input, {});
    autocomplete.bindTo("bounds", map);
    const marker = new google.maps.Marker({
      icon: "http://maps.google.com/mapfiles/ms/icons/green-dot.png",
      map,
      anchorPoint: new google.maps.Point(0, -29),
    });

    autocomplete.addListener("place_changed", () => {
      marker.setVisible(false);
      const place = autocomplete.getPlace();
      if (!place.geometry || !place.geometry.location) {
        window.alert("No details available for input: '" + place.name + "'");
        return;
      }
      // Set address, lat, long
      input.value = place.formatted_address || place.name;
      document.getElementById('lat').value = place.geometry.location.lat();
      document.getElementById('long').value = place.geometry.location.lng();
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

  window.initMap = initMap;
</script>
 

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
</script>

<!-- Select2 resources -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2-multiple').select2({
            placeholder: "Select payment methods",
            allowClear: true,
            width: '100%'
        });
    });
</script>
@endpush
@endsection
