<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Menghapus Route::get('/user') yang tertimpa, langsung menggunakan redirect agar clean
Route::redirect('/user', '/user/routes')->name('user.home');

Route::prefix('user')->name('user.')->group(function () {
    Route::view('/routes', 'user.routes')->name('routes');
    Route::view('/payments', 'user.payments')->name('payments');
    Route::view('/my-trip', 'user.my-trip')->name('my-trip');
    Route::view('/favourites', 'user.favourites')->name('favourites');
    Route::redirect('/favorites', '/user/favourites')->name('favorites');
    
    // Rute settings ditambahkan di sini
    Route::view('/settings', 'settings')->name('settings');
});