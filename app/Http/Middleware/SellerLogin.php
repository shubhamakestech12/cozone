<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SellerLogin
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
        $seller_type = session('seller_type');
        $seller_data = session('seller_data');
        $is_login = session('is_login');

        if(isset($seller_data) && !empty($seller_data) && !empty($seller_type)) {
            $sesison_id = md5((md5(session('seller_data')->mob_no)));
            //md5((md5($user_data->email))),
            if($is_login === TRUE && session('session_id') == $sesison_id && $seller_type === 'seller') {
                return $next($request);
            } else {
                return redirect('seller-login');
            }
        } else {
            return redirect('seller-login');
        }

    }
}
