<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

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
        $user = User::find(Auth::id()); 

        if (!$user) {
            return redirect()->route('login')->with('error', 'You need to log in first.');
        }
        if ($request->routeIs('login') || $request->routeIs('register')) {
            return redirect()->route($user->hasRole('admin') ? 'dashboard' : 'home');
        }

        if ($request->routeIs('dashboard') || str_starts_with($request->path(), 'admin')) {
            if (!$user->hasRole('admin')) {
                return redirect()->route('home')->with('error', 'Unauthorized access');
            }
        }

        return $next($request);
    }
}