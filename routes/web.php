<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\UniversityController;

// Public Routes - No Authentication Required
Route::middleware(['web','preventBackHistory'])->group(function () {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('/');
    Route::get('/home', function() {
        return redirect('/');
    });
    
    // Public Product Routes
    Route::get('/products', [App\Http\Controllers\ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [App\Http\Controllers\ProductController::class, 'show'])->name('products.show');
    Route::get('/products/category/{category}', [App\Http\Controllers\ProductController::class, 'byCategory'])->name('products.category');
    

    Route::get('/contact', [App\Http\Controllers\ContactController::class, 'show'])->name('contact');
    Route::post('/contact', [App\Http\Controllers\ContactController::class, 'submit'])->name('contact.submit');

    Route::get('/about', [App\Http\Controllers\ContactController::class, 'aboutus'])->name('about');
    Route::get('/privacy', [App\Http\Controllers\ContactController::class, 'privacy'])->name('privacy');
    Route::get('/terms', [App\Http\Controllers\ContactController::class, 'terms'])->name('terms');

});

// Authentication Routes (User)
Route::middleware(['preventBackHistory'])->group(function () {
    Auth::routes();
});

// Password Reset Routes
Route::get('password/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])
    ->name('password.request');
Route::post('password/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->name('password.email');
Route::get('password/reset/{token}', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showResetForm'])
    ->name('password.reset');
Route::post('password/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'reset'])
    ->name('password.update');

// Email verification route
Route::get('/email/verify/{token}', [App\Http\Controllers\Auth\RegisterController::class, 'verifyEmail'])
    ->name('verification.verify');

    Route::get('fetchUniversities', [App\Http\Controllers\Auth\RegisterController::class, 'fetchUniversities']);

// User Protected Routes - Regular Users
Route::middleware(['web', 'auth','preventBackHistory'])->group(function () {
    // Product reporting route
    Route::post('/products/{product}/report', [App\Http\Controllers\ProductController::class, 'report'])
        ->name('products.report');

    // Seller Routes
    Route::prefix('my-products')->name('seller.products')->group(function () {
        Route::get('/', [App\Http\Controllers\SellerController::class, 'index']);
        Route::get('/create', [App\Http\Controllers\SellerController::class, 'create'])->name('.create');
        Route::post('/', [App\Http\Controllers\SellerController::class, 'store'])->name('.store');
        Route::get('/{product}/edit', [App\Http\Controllers\SellerController::class, 'edit'])->name('.edit');
        Route::put('/{product}', [App\Http\Controllers\SellerController::class, 'update'])->name('.update');
        Route::delete('/{product}', [App\Http\Controllers\SellerController::class, 'destroy'])->name('.destroy');
        Route::post('/{product}/reserve', [App\Http\Controllers\SellerController::class, 'reserve'])->name('.reserve');
        Route::post('{product}/markAsSold', [App\Http\Controllers\SellerController::class, 'markAsSold'])->name('.mark_sold');
    });

    // Messages
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', [App\Http\Controllers\ChatController::class, 'index'])->name('index');
        Route::get('/{chat}', [App\Http\Controllers\ChatController::class, 'show'])->name('show');
        Route::post('/{product}/start', [App\Http\Controllers\ChatController::class, 'startConversation'])->name('start');
        Route::post('/{chat}/send', [App\Http\Controllers\ChatController::class, 'send'])->name('send');
        Route::get('/{chat}/poll', [App\Http\Controllers\ChatController::class, 'pollMessages'])->name('poll');
    });

    // Transactions
    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::get('/', [App\Http\Controllers\TransactionController::class, 'index'])->name('index');
        Route::get('/{transaction}', [App\Http\Controllers\TransactionController::class, 'show'])->name('show');
        Route::get('/{product}/initiate', [App\Http\Controllers\TransactionController::class, 'initiateForm'])->name('initiate.form');
        Route::post('/{product}/initiate', [App\Http\Controllers\TransactionController::class, 'initiate'])->name('initiate');
        Route::post('/{transaction}/complete', [App\Http\Controllers\TransactionController::class, 'complete'])->name('complete');
        Route::post('/{transaction}/cancel', [App\Http\Controllers\TransactionController::class, 'cancel'])->name('cancel');
    });

    // Meetings
    Route::prefix('meetings')->name('meetings.')->group(function () {
        Route::get('/', [App\Http\Controllers\MeetingController::class, 'index'])->name('index');
        Route::post('/{transaction}/schedule', [App\Http\Controllers\MeetingController::class, 'schedule'])->name('schedule');
        Route::put('/{meeting}', [App\Http\Controllers\MeetingController::class, 'update'])->name('update');
    });

    // Profile Routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [App\Http\Controllers\ProfileController::class, 'show'])->name('show');
        Route::put('/', [App\Http\Controllers\ProfileController::class, 'update'])->name('update');
        Route::post('/verify-email', [App\Http\Controllers\ProfileController::class, 'sendEmailVerification'])->name('verify.email');
        Route::post('/verify-otp', [App\Http\Controllers\ProfileController::class, 'verifyOtp'])->name('verify.otp');
        Route::post('/update-image', [App\Http\Controllers\ProfileController::class, 'updateImage'])->name('update.image');
        
        // Profile Settings Routes
        Route::get('/settings', [App\Http\Controllers\ProfileSettingsController::class, 'index'])->name('settings');
        Route::put('/settings/password', [App\Http\Controllers\ProfileSettingsController::class, 'updatePassword'])->name('update-password');
        Route::delete('/settings/account', [App\Http\Controllers\ProfileSettingsController::class, 'deleteAccount'])->name('delete-account');
    });
});

// Admin Routes - Public Entry Point
Route::prefix('admin')->middleware(['web','preventBackHistory'])->group(function () {
    Route::get('/', function () {
        return Auth::guard('admin')->check() 
            ? redirect()->route('admin.dashboard')
            : redirect()->route('admin.login');
    });

    // Admin Auth Routes
    Route::middleware(['guest:admin','preventBackHistory'])->group(function () {
        Route::get('login', [App\Http\Controllers\Auth\AdminAuthController::class, 'showLoginForm'])->name('admin.login');
        Route::post('login', [App\Http\Controllers\Auth\AdminAuthController::class, 'login']);
    });
});

// Admin Protected Routes - Admin Users Only
Route::middleware(['web', 'auth:admin','preventBackHistory'])->prefix('admin')->name('admin.')->group(function () {
    Route::post('logout', [App\Http\Controllers\Auth\AdminAuthController::class, 'logout'])->name('logout');
    Route::get('dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Products Routes
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\ProductManagementController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\ProductManagementController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Admin\ProductManagementController::class, 'store'])->name('store');
        Route::get('/{product}/edit', [App\Http\Controllers\Admin\ProductManagementController::class, 'edit'])->name('edit');
        Route::put('/{product}', [App\Http\Controllers\Admin\ProductManagementController::class, 'update'])->name('update');
        Route::delete('/{product}', [App\Http\Controllers\Admin\ProductManagementController::class, 'destroy'])->name('destroy');
        
        // Product Status Routes
        Route::post('/{id}/approve', [App\Http\Controllers\Admin\ProductManagementController::class, 'approveById'])->name('approve');
        Route::post('/{id}/reject', [App\Http\Controllers\Admin\ProductManagementController::class, 'rejectById'])->name('reject');
        Route::post('/{id}/flag', [App\Http\Controllers\Admin\ProductManagementController::class, 'flagById'])->name('flag');
        Route::patch('/{id}/status', [App\Http\Controllers\Admin\ProductManagementController::class, 'updateStatus'])->name('status');
        Route::post('/upload-limit', [App\Http\Controllers\Admin\ProductManagementController::class, 'setUploadLimit'])->name('upload-limit');
        
        // Product Limit Management
        Route::get('/limits', [App\Http\Controllers\Admin\ProductManagementController::class, 'productLimits'])->name('limits');
        Route::post('/users/{user}/limit', [App\Http\Controllers\Admin\ProductManagementController::class, 'setUserProductLimit'])->name('set-user-limit');
        Route::post('/users/{user}/reset-limit', [App\Http\Controllers\Admin\ProductManagementController::class, 'resetUserProductLimit'])->name('reset-user-limit');
        
        Route::get('report', [App\Http\Controllers\Admin\ProductManagementController::class, 'productreport'])->name('report');
        Route::get('inquiry', [App\Http\Controllers\Admin\ProductManagementController::class, 'inquiry'])->name('inquiry');
    });
    
    // Users Routes
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('destroy');
        Route::post('/{user}/block', [App\Http\Controllers\Admin\UserController::class, 'block'])->name('block');
        Route::patch('/{user}/status', [App\Http\Controllers\Admin\UserController::class, 'updateStatus'])->name('update-status');
        
        // User Permissions Routes
        Route::get('/permissions', [App\Http\Controllers\Admin\UserPermissionController::class, 'index'])->name('permissions');
        Route::get('/{user}/permissions', [App\Http\Controllers\Admin\UserPermissionController::class, 'edit'])->name('permissions.edit');
        Route::post('/{user}/permissions', [App\Http\Controllers\Admin\UserPermissionController::class, 'update'])->name('permissions.update');
        Route::post('/{user}/roles', [App\Http\Controllers\Admin\UserPermissionController::class, 'updateRoles'])->name('roles.update');
    });
    
    // Transaction Routes
    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\TransactionController::class, 'index'])->name('index');
        Route::get('/issues', [App\Http\Controllers\Admin\TransactionController::class, 'issues'])->name('issues');
        Route::get('/{transaction}', [App\Http\Controllers\Admin\TransactionController::class, 'show'])->name('show');
        Route::post('/{transaction}/resolve', [App\Http\Controllers\Admin\TransactionController::class, 'resolve'])->name('resolve');
    });
    
    // Message Monitoring Routes
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\MessageController::class, 'index'])->name('index');
        Route::get('/history', [App\Http\Controllers\Admin\MessageController::class, 'chatHistory'])->name('history');
        Route::get('/export', [App\Http\Controllers\Admin\MessageController::class, 'export'])->name('export');
        Route::get('/{productId}/{userId1}/{userId2}', [App\Http\Controllers\Admin\MessageController::class, 'show'])->name('show');
        Route::post('/{chat}/review', [App\Http\Controllers\Admin\MessageController::class, 'review'])->name('review');
    });
    
    // Categories Routes
    Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);
    
    // Roles and Permissions Routes
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\RoleController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\RoleController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Admin\RoleController::class, 'store'])->name('store');
        Route::get('/{role}/edit', [App\Http\Controllers\Admin\RoleController::class, 'edit'])->name('edit');
        Route::put('/{role}', [App\Http\Controllers\Admin\RoleController::class, 'update'])->name('update');
        Route::delete('/{role}', [App\Http\Controllers\Admin\RoleController::class, 'destroy'])->name('destroy');
        Route::post('/{role}/permissions', [App\Http\Controllers\Admin\RoleController::class, 'updatePermissions'])->name('permissions.update');
    });
    
    Route::get('/permissions', [App\Http\Controllers\Admin\PermissionController::class, 'index'])->name('permissions.index');
    
    // Admin profile and settings routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\ProfileController::class, 'index'])->name('index');
        Route::put('/', [App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('update');
        Route::post('/avatar', [App\Http\Controllers\Admin\ProfileController::class, 'updateAvatar'])->name('avatar');
    });
    
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('index');
        Route::post('/', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('update');
        Route::post('/product-limit', [App\Http\Controllers\Admin\SettingsController::class, 'updateProductLimit'])->name('productLimit');
    });
    
    // Report Management Routes
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('index');
        Route::get('/{report}', [App\Http\Controllers\Admin\ReportController::class, 'show'])->name('show');
        Route::post('/{report}/resolve', [App\Http\Controllers\Admin\ReportController::class, 'resolve'])->name('resolve');
    });
    
    // Contact Management Routes
    Route::prefix('contacts')->name('contacts.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\ContactController::class, 'index'])->name('index');
        Route::get('/{contact}', [App\Http\Controllers\Admin\ContactController::class, 'show'])->name('show');
        Route::post('/{contact}/respond', [App\Http\Controllers\Admin\ContactController::class, 'markAsResponded'])->name('respond');
    });
    
    // University Management
    Route::prefix('universities')->name('universities.')->group(function () {
        Route::get('/data', [UniversityController::class, 'getData'])->name('data');
    });
    Route::resource('universities', UniversityController::class);
});

// Admin CMS Pages Routes
Route::middleware(['web', 'auth:admin','preventBackHistory'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('cms', App\Http\Controllers\Admin\CmsPageController::class);
});
Route::post('/save-location', [App\Http\Controllers\HomeController::class, 'saveLocation']);
// Route::get('/check-messages', 'ChatController@checkNewMessages')->name('messages.check');
Route::get('/check-messages', [App\Http\Controllers\ChatController::class, 'checkNewMessages'])->name('messages.check');

// Email verification route
Route::any('/resendsignup-verify-email', [App\Http\Controllers\Auth\RegisterController::class, 'resendVerificationEmail'])
    ->name('resendsignup.verify.email');


Route::get('/unread-product-count', [App\Http\Controllers\ChatController::class, 'getUnreadCountByProduct'])->name('messages.unread_product_count');

