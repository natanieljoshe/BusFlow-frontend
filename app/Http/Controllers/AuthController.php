<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    private function getApiUrl()
    {
        return env('API_URL', 'http://127.0.0.1:8010/api');
    }

    public function login(Request $request)
    {
        $response = Http::withHeaders(['Accept' => 'application/json'])
            ->post($this->getApiUrl() . '/login', [
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if ($response->successful()) {
            $data = $response->json('data');
            $token = $data['token'] ?? null;
            
            if ($token) {
                session(['api_token' => $token]);
                
                // Fetch User Details to get role
                $userRes = Http::withToken($token)->get($this->getApiUrl() . '/me');
                $role = 'passenger';
                if ($userRes->successful()) {
                    $userData = $userRes->json('data');
                    if (isset($userData['role'])) {
                        $role = $userData['role'];
                    }
                    session(['user' => $userData]);
                }
                
                return response()->json(['status' => 'success', 'role' => $role]);
            }
        }

        return response()->json([
            'status' => 'error', 
            'message' => $response->json('message') ?? 'Invalid credentials (HTTP ' . $response->status() . ')',
            'debug' => $response->body()
        ], 401);
    }

    public function register(Request $request)
    {
        $response = Http::post($this->getApiUrl() . '/register', [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'password_confirmation' => $request->password_confirmation,
        ]);

        if ($response->successful()) {
            $data = $response->json('data');
            $token = $data['token'] ?? null;
            
            if ($token) {
                session(['api_token' => $token]);
                
                // Fetch User Details to get role
                $userRes = Http::withToken($token)->get($this->getApiUrl() . '/me');
                $role = 'passenger';
                if ($userRes->successful()) {
                    $userData = $userRes->json('data');
                    if (isset($userData['role'])) {
                        $role = $userData['role'];
                    }
                    session(['user' => $userData]);
                }
                
                return response()->json(['status' => 'success', 'role' => $role]);
            }
        }

        return response()->json([
            'status' => 'error', 
            'message' => $response->json('message') ?? 'Registration failed',
            'errors' => $response->json('errors')
        ], $response->status() === 422 ? 422 : 400);
    }

    public function googleSuccess(Request $request)
    {
        $token = $request->query('token');
        if (!$token) {
            return redirect('/login')->with('error', 'Google Login Failed. No token received.');
        }

        session(['api_token' => $token]);
        
        // Fetch User Details to get role
        $userRes = Http::withToken($token)->get($this->getApiUrl() . '/me');
        $role = 'passenger';
        if ($userRes->successful()) {
            $userData = $userRes->json('data');
            if (isset($userData['role'])) {
                $role = $userData['role'];
            }
            session(['user' => $userData]);
        }
        
        switch ($role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'operator':
                return redirect()->route('admin.routes');
            case 'driver':
                return redirect()->route('sopir.home');
            case 'conductor':
                return redirect()->route('sopir.home');
            case 'passenger':
            default:
                return redirect('/user');
        }
    }

    public function logout(Request $request)
    {
        // Optionally, call backend logout if backend supports it
        // Http::withToken(session('api_token'))->post($this->getApiUrl() . '/logout');

        session()->forget(['api_token', 'user']);
        return redirect('/');
    }
}
