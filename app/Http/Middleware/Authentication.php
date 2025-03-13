<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class Authentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'You need to log in first.');
        }
    
        if ($request->routeIs('login') || $request->routeIs('register')) {
            return redirect()->route($user->role_id == 1 ? 'dashboard' : 'home');
        }
    
        if (str_starts_with($request->path(), 'admin') || $request->routeIs('dashboard')) {
            if ($user->role_id != 1) {
                return redirect()->route('home')->with('error', 'Unauthorized access');
            }
        }
    
        if ($request->routeIs('home')) {
            if ($user->role_id == 1) {
                return redirect()->route('dashboard')->with('error', 'Admins cannot access homepage');
            }
        }
    
        return $next($request);
    }
}