<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Kurir
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::guard('web')->user();
        
        if (!$user || !$user->isKurir()) {
            return redirect('/dashboard')->with('error', 'Akses tidak diizinkan. Hanya untuk Kurir.');
        }
        
        return $next($request);
    }
}