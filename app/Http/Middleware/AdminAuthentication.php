<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // dd(Auth::user());
        return $next($request);

        $user = Auth::user();

        // Ensure user is authenticated and has the required role/permissions
        if ($user && ($user->hasRole(['administrator', 'superadmin']) || $user->hasPermissionTo('access-admin-panel'))) {
            return $next($request);
        }
        // if(Auth::user()->user_category == 100 && Auth::user()->profile != null && Auth::user()->profile->profile_category == 'Administrator' || Auth::user()->profile->profile_category == 'administrator' || Auth::user()->profile->profile_category == 'admin'){
        //     // info('YES');
        //      return $next($request);
        //  }else{
        //      return redirect()->back()->with('error','Permission Denied');
        //  }
    }
}
