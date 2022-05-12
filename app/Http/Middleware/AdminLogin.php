<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user_type = session('user_type');
        $admin_data = session('admin_data');
        $is_login = session('is_login');

        if(isset($admin_data) && !empty($admin_data) && !empty($user_type)) {
            $sesison_id = md5((md5(session('admin_data')->email)));
            if($is_login === TRUE && session('session_id') == $sesison_id && $user_type === 'admin') {
                return $next($request);
            } else {
                return redirect('admin-login');
            }
        } else {
            return redirect('admin-login');
        }

    }
}
