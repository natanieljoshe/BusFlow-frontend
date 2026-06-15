<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
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