<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfUnverifiedStudent
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user()->isVerified()) {
            return redirect()->route('profile.show')
                ->with('error', 'Please verify your university email first.');
        }

        return $next($request);
    }
}
