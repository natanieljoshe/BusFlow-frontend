<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = session('user');

        if (!$user) {
            return redirect('/login');
        }

        if (!in_array($user['role'] ?? '', $roles)) {
            // Redirect based on role if they are not authorized for this specific route
            $role = $user['role'] ?? 'passenger';
            switch ($role) {
                case 'admin':
                case 'operator':
                    return redirect('/admin/dashboard');
                case 'driver':
                case 'conductor':
                    return redirect('/sopir/home');
                case 'passenger':
                default:
                    return redirect('/user');
            }
        }

        return $next($request);
    }
}
