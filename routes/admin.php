<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FeatureController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\RegionController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\VillaController;
use App\Http\Controllers\Admin\VillaTypeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Panel Routes
|--------------------------------------------------------------------------
*/

// Auth Routes (Guest only)
Route::middleware('guest:admin')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'login'])->name('admin.login.post');
});

// Protected Routes
Route::middleware('auth:admin')->group(function () {
    
    // Logout
    Route::get('/logout', [AuthController::class, 'logout'])->name('admin.logout');
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // Profile (bilgilerim)
    Route::get('/profile', function() {
        return view('admin.profile');
    })->name('admin.profile');
    
    // Reservations
    Route::prefix('reservations')->name('admin.reservations.')->group(function () {
        Route::get('/', [ReservationController::class, 'index'])->name('index');
        Route::get('/external', [ReservationController::class, 'external'])->name('external');
        Route::get('/calendar', [ReservationController::class, 'calendar'])->name('calendar');
        Route::get('/create', [ReservationController::class, 'create'])->name('create');
        Route::post('/', [ReservationController::class, 'store'])->name('store');
        Route::get('/{reservation}/edit', [ReservationController::class, 'edit'])->name('edit');
        Route::put('/{reservation}', [ReservationController::class, 'update'])->name('update');
        Route::delete('/{reservation}', [ReservationController::class, 'destroy'])->name('destroy');
        Route::patch('/{reservation}/status', [ReservationController::class, 'updateStatus'])->name('status');
        
        // AJAX endpoints
        Route::post('/calculate-price', [ReservationController::class, 'calculatePrice'])->name('calculate-price');
        Route::post('/get-occupied-dates', [ReservationController::class, 'getOccupiedDates'])->name('get-occupied-dates');
    });
    
    // Villas
    Route::prefix('villas')->name('admin.villas.')->group(function () {
        Route::get('/', [VillaController::class, 'index'])->name('index');
        Route::get('/ordering', [VillaController::class, 'ordering'])->name('ordering');
        Route::post('/ordering', [VillaController::class, 'updateOrdering'])->name('ordering.update');
        Route::get('/create', [VillaController::class, 'create'])->name('create');
        Route::post('/', [VillaController::class, 'store'])->name('store');
        Route::get('/{villa}/edit', [VillaController::class, 'edit'])->name('edit');
        Route::put('/{villa}', [VillaController::class, 'update'])->name('update');
        Route::delete('/{villa}', [VillaController::class, 'destroy'])->name('destroy');
        Route::get('/{villa}/duplicate', [VillaController::class, 'duplicate'])->name('duplicate');
        
        // Gallery
        Route::post('/{villa}/images', [VillaController::class, 'uploadImage'])->name('images.upload');
        Route::delete('/images/{image}', [VillaController::class, 'deleteImage'])->name('images.delete');
        
        // Seasonal Prices
        Route::post('/{villa}/prices', [VillaController::class, 'storePrice'])->name('prices.store');
        Route::delete('/prices/{price}', [VillaController::class, 'deletePrice'])->name('prices.delete');
    });
    
    // Regions
    Route::resource('regions', RegionController::class)
        ->names('admin.regions')
        ->except(['show']);
    
    // Villa Types
    Route::resource('villa-types', VillaTypeController::class)
        ->names('admin.villa-types')
        ->except(['show']);
    
    // Technical Features
    Route::resource('features', FeatureController::class)
        ->names('admin.features')
        ->except(['show']);
    
    // Payment Methods
    Route::resource('payment-methods', PaymentMethodController::class)
        ->names('admin.payment-methods')
        ->except(['show']);
    
    // Additional Services
    Route::resource('services', ServiceController::class)
        ->names('admin.services')
        ->except(['show']);
    
    // Contact Messages
    Route::prefix('contact')->name('admin.contact.')->group(function () {
        Route::get('/', [ContactController::class, 'index'])->name('index');
        Route::get('/{message}', [ContactController::class, 'show'])->name('show');
        Route::delete('/{message}', [ContactController::class, 'destroy'])->name('destroy');
    });
    
    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings');
    Route::put('/settings', [SettingController::class, 'update'])->name('admin.settings.update');

    // About Us
    Route::get('/about', [\App\Http\Controllers\Admin\AboutController::class, 'index'])->name('admin.about.index');
    Route::put('/about', [\App\Http\Controllers\Admin\AboutController::class, 'update'])->name('admin.about.update');

    // Photo Gallery
    Route::prefix('gallery')->name('admin.gallery.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\PhotoGalleryController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\PhotoGalleryController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\PhotoGalleryController::class, 'store'])->name('store');
        Route::get('/{gallery}/edit', [\App\Http\Controllers\Admin\PhotoGalleryController::class, 'edit'])->name('edit');
        Route::put('/{gallery}', [\App\Http\Controllers\Admin\PhotoGalleryController::class, 'update'])->name('update');
        Route::delete('/{gallery}', [\App\Http\Controllers\Admin\PhotoGalleryController::class, 'destroy'])->name('destroy');
        
        Route::post('/{gallery}/images', [\App\Http\Controllers\Admin\PhotoGalleryController::class, 'uploadImage'])->name('images.upload');
        Route::delete('/images/{image}', [\App\Http\Controllers\Admin\PhotoGalleryController::class, 'deleteImage'])->name('images.delete');
    });

    // Services (Home)
    Route::resource('services-home', \App\Http\Controllers\Admin\HomePageServiceController::class)
        ->names('admin.services-home')
        ->except(['show']);

    // Reviews
    Route::resource('reviews', \App\Http\Controllers\Admin\ReviewController::class)
        ->names('admin.reviews')
        ->except(['show']);

    // Blog
    Route::resource('blog', \App\Http\Controllers\Admin\BlogController::class)
        ->names('admin.blog')
        ->except(['show']);
});
