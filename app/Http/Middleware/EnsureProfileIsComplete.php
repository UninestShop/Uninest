<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureProfileIsComplete
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Skip middleware if user is not authenticated
        if (!Auth::check()) {
            return $next($request);
        }
        
        $user = Auth::user();
        
        // Check if profile is complete
        $isComplete = 
            !empty($user->name) && 
            !empty($user->mobile_number) && 
            !empty($user->gender) && 
            !empty($user->dob);
        
        // If profile is complete, proceed
        if ($isComplete) {
            return $next($request);
        }
        
        // Always allow settings page access
        $path = $request->path();
       
        
        // Only allow these specific routes when profile is incomplete
        $allowedRoutes = [
            'profile.show',
            'profile.update', 
            'profile.update.image',
            'logout'
        ];
        
        // Get current route name
        $currentRoute = $request->route() ? $request->route()->getName() : '';
        
        // Check if current route is in allowed routes
        $isAllowedRoute = in_array($currentRoute, $allowedRoutes);
        
        // If accessing an allowed route, proceed
        if ($isAllowedRoute) {
            return $next($request);
        }
        
        // Otherwise redirect to profile page with error
        return redirect()->route('profile.show')
            ->with('error', 'Please complete your profile information before proceeding.');
    }
}
