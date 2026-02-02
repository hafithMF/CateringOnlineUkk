<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminOwner
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::guard('web')->user();
        
        if (!$user || (!$user->isAdmin() && !$user->isOwner())) {
            return redirect('/dashboard')->with('error', 'Akses tidak diizinkan. Hanya untuk Admin/Owner.');
        }
        
        return $next($request);
    }
}