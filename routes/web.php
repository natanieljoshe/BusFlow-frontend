<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

// Auth Routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/local-login', [AuthController::class, 'login'])->name('local.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/auth/google/success', [AuthController::class, 'googleSuccess'])->name('local.google.success');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/local-register', [AuthController::class, 'register'])->name('local.register');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () { return view('admin.dashboard'); })->name('dashboard');
    Route::get('/fleet', function () { return view('admin.fleet'); })->name('fleet');
    Route::get('/fleet/{id}', function ($id) { return view('admin.fleet-detail', compact('id')); })->name('fleet.detail');
    Route::get('/routes', function () { return view('admin.routes'); })->name('routes');
    Route::get('/routes/{id}', function ($id) { return view('admin.routes-detail', compact('id')); })->name('routes.detail');
    Route::get('/haltes', function () { return view('admin.haltes'); })->name('haltes');
    Route::get('/users', function () { return view('admin.users'); })->name('users');
    Route::get('/staff', function () { return view('admin.staff'); })->name('staff');
    Route::get('/staff/{type}/{id}', function ($type, $id) { return view('admin.staff-profile', compact('type', 'id')); })->name('staff.profile');
    // Route::get('/schedule', function () { return view('admin.schedule'); })->name('schedule');
    Route::get('/analytics', function () { return view('admin.analytics'); })->name('analytics');
    Route::get('/boarding-scanner', function () { return view('admin.boarding-scanner'); })->name('boarding-scanner');
    Route::get('/ga-optimizer', function () { return view('admin.ga-optimizer'); })->name('ga-optimizer');
    Route::get('/ga-results', function (\Illuminate\Http\Request $request) { 
        $jobId = $request->query('job_id');
        $payload = [];
        
        if ($jobId) {
            $path = base_path('../bus_flow_backend/BusFlow-backend/storage/app/optimizations/' . $jobId . '.json');
            if (file_exists($path)) {
                $statusData = json_decode(file_get_contents($path), true);
                if (isset($statusData['result'])) {
                    $payload = $statusData['result'];
                }
            }
        }
        
        // Fallback for direct visits
        if (empty($payload)) {
            $fallbackPath = base_path('../bus_flow_backend/BusFlow-backend/app/Algorithms/src/payload.JSON');
            if (file_exists($fallbackPath)) {
                $payload = json_decode(file_get_contents($fallbackPath), true);
            }
        }

        return view('admin.ga-results', compact('payload'));
    })->name('ga-results');
});

Route::redirect('/user', '/user/routes')->name('user.home');

Route::prefix('user')->name('user.')->group(function () {
    Route::get('/routes', [UserDashboardController::class, 'routes'])->name('routes');
    Route::get('/routes/details/{id?}', [UserDashboardController::class, 'routeDetails'])->name('routes.details');
    Route::get('/busstop/{uid?}', [UserDashboardController::class, 'busstop'])->name('busstop');
    Route::get('/boarding-scan', [UserDashboardController::class, 'boardingScan'])->name('boarding-scan');
    Route::get('/my-trip', [UserDashboardController::class, 'myTrip'])->name('my-trip');
    Route::get('/my-wallet', [UserDashboardController::class, 'myWallet'])->name('my-wallet');
    Route::get('/notifications', [UserDashboardController::class, 'notifications'])->name('notifications');
});

Route::prefix('sopir')->name('sopir.')->group(function () {
    Route::view('/home', 'sopir.sopir_home')->name('home');
    Route::view('/trip-details', 'sopir.sopir_trip_details')->name('trip-details');
    Route::view('/history', 'sopir.sopir_history')->name('history');
    Route::view('/boarding-scanner', 'sopir.sopir_boarding_scanner')->name('boarding-scanner');
});