<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use Auth;

class CheckAdmin
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
        // dd($request->all());
        $user = User::where('email', $request->email)->first();
        // dd($user);
        if ($user->is_admin === 1) {
            // dd($next($request));
            return $next($request);
        }else{
            // dd('else');
        return redirect('admin/login');
        }
    }
}
