<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Owner
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::guard('web')->user();
        
        if (!$user || !$user->isOwner()) {
            return redirect('/dashboard')->with('error', 'Akses tidak diizinkan. Hanya untuk Owner.');
        }
        
        return $next($request);
    }
}