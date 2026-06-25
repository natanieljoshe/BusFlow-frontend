<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Auth Routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {

    // Dashboard & Pages
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/fleet', function () {
        return view('admin.fleet');
    })->name('fleet');

    Route::get('/staff', function () {
        return view('admin.staff');
    })->name('staff');

    Route::get('/schedule', function () {
        return view('admin.schedule');
    })->name('schedule');

    Route::get('/analytics', function () {
        return view('admin.analytics');
    })->name('analytics');
});

// Menghapus Route::get('/user') yang tertimpa, langsung menggunakan redirect agar clean
Route::redirect('/user', '/user/routes')->name('user.home');

Route::prefix('user')->name('user.')->group(function () {
    Route::view('/routes', 'user.user_routes')->name('routes');
    Route::view('/payments', 'user.user_payments')->name('payments');
    Route::view('/my-trip', 'user.user_my_trip')->name('my-trip');
    Route::view('/favourites', 'user.user_favourites')->name('favourites');
    Route::redirect('/favorites', '/user/favourites')->name('favorites');
    Route::view('/settings', 'user.user_settings')->name('settings');
});

Route::prefix('sopir')->name('sopir.')->group(function () {
    Route::view('/home', 'sopir.sopir_home')->name('home');
    Route::view('/trip-details', 'sopir.sopir_trip_details')->name('trip-details');
    Route::view('/history', 'sopir.sopir_history')->name('history');
    Route::view('/settings', 'sopir.sopir_settings')->name('settings');
});