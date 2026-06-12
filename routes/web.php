<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Admin Routes (Tahap 1)
Route::prefix('admin')->name('admin.')->group(function () {
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
