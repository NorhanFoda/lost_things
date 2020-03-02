<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class AdminLlogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ( Auth::check()) {
            // return redirect()->route('admin_login');
            return $next($request);
        }else{
            
            return redirect()->route('admin_login');
            }
        
    }
}
