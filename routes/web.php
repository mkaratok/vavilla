<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\VillaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Static Pages
Route::get('/hakkimizda', [HomeController::class, 'about'])->name('about');
Route::get('/iletisim', [HomeController::class, 'contact'])->name('contact');
Route::get('/galeri', [HomeController::class, 'gallery'])->name('gallery');
Route::get('/galeri/{slug}', [HomeController::class, 'galleryShow'])->name('gallery.show');
Route::get('/sss', [HomeController::class, 'faq'])->name('faq');

// Villa Routes
Route::get('/villalar', [VillaController::class, 'index'])->name('villas.index');
Route::get('/villa/{slug}', [VillaController::class, 'show'])->name('villas.show');
Route::get('/bolge/{slug}', [VillaController::class, 'byRegion'])->name('villas.region');
Route::get('/villa-tipi/{slug}', [VillaController::class, 'byType'])->name('villas.type');

// Reservation Routes
Route::get('/rezervasyon/{slug}', [ReservationController::class, 'create'])->name('reservation.create');
Route::post('/rezervasyon/{slug}', [ReservationController::class, 'store'])->name('reservation.store');
Route::get('/rezervasyon-onay', [ReservationController::class, 'success'])->name('reservation.success');

// AJAX Routes
Route::post('/api/check-availability', [ReservationController::class, 'checkAvailability'])->name('api.check-availability');
Route::post('/api/calculate-price', [ReservationController::class, 'calculatePrice'])->name('api.calculate-price');
Route::post('/api/occupied-dates', [ReservationController::class, 'getOccupiedDates'])->name('api.occupied-dates');

// Contact
Route::post('/iletisim', [ContactController::class, 'store'])->name('contact.store');
Route::post('/iletisim-gonder', [ContactController::class, 'store'])->name('contact.submit');
Route::post('/newsletter', [ContactController::class, 'newsletter'])->name('newsletter.store');
