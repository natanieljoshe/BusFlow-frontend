<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class UserDashboardController extends Controller
{
    private function getApiUrl()
    {
        return env('API_URL', 'http://127.0.0.1:8010/api');
    }

    private function getHeaders()
    {
        $token = session('api_token');
        if ($token) {
            return ['Authorization' => 'Bearer ' . $token];
        }
        return [];
    }

    public function routes(Request $request): View
    {
        $queryParams = $request->only(['origin', 'destination']);
        $response = Http::withHeaders($this->getHeaders())->get($this->getApiUrl() . '/routes', $queryParams);
        $routes = $response->successful() ? $response->json('data') : [];
        
        return view('user.user_routes', compact('routes'));
    }

    public function routeDetails($id): View
    {
        $response = Http::withHeaders($this->getHeaders())->get($this->getApiUrl() . '/routes/' . $id);
        $route = $response->successful() ? $response->json('data') : null;
        
        $tripsRes = Http::withHeaders($this->getHeaders())->get($this->getApiUrl() . '/trips', ['route_id' => $id]);
        $trips = $tripsRes->successful() ? $tripsRes->json('data') : [];
        
        return view('user.user_routes_details', compact('route', 'trips', 'id'));
    }

    public function busstop($uid): View
    {
        return view('user.user_busstop', compact('uid'));
    }

    public function boardingScan(Request $request): View
    {
        $wallet = null;
        $history = [];
        $bookings = [];
        $allBookings = [];

        if (session('api_token')) {
            $walletRes = Http::withHeaders($this->getHeaders())->get($this->getApiUrl() . '/wallet');
            if ($walletRes->successful()) {
                $wallet = $walletRes->json('data');
            }

            $historyRes = Http::withHeaders($this->getHeaders())->get($this->getApiUrl() . '/wallet/history');
            if ($historyRes->successful()) {
                $history = $historyRes->json('data');
            }

            $bookingsRes = Http::withHeaders($this->getHeaders())->get($this->getApiUrl() . '/bookings');
            if ($bookingsRes->successful()) {
                $allBookings = $bookingsRes->json('data');
                $bookings = collect($allBookings)->whereIn('status', ['booked', 'active'])->values()->all();
            }
        }
        
        return view('user.user_boarding_scan', compact('wallet', 'history', 'bookings', 'allBookings'));
    }

    public function myTrip(Request $request): View
    {
        $bookings = [];
        if (session('api_token')) {
            $bookingsRes = Http::withHeaders($this->getHeaders())->get($this->getApiUrl() . '/bookings');
            if ($bookingsRes->successful()) {
                $bookings = collect($bookingsRes->json('data'))->where('status', 'completed')->values()->all();
            }
        }
        
        return view('user.user_my_trip', compact('bookings'));
    }

    public function myWallet(Request $request): View
    {
        $wallet = null;
        $history = [];

        if (session('api_token')) {
            $walletRes = Http::withHeaders($this->getHeaders())->get($this->getApiUrl() . '/wallet');
            if ($walletRes->successful()) {
                $wallet = $walletRes->json('data');
            }

            $historyRes = Http::withHeaders($this->getHeaders())->get($this->getApiUrl() . '/wallet/history');
            if ($historyRes->successful()) {
                $history = $historyRes->json('data');
            }
        }

        return view('user.user_my_wallet', compact('wallet', 'history'));
    }
}
