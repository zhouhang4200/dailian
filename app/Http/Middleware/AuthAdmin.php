<?php

namespace App\Http\Middleware;

use Closure;

class AuthAdmin
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
        if(auth()->guard('admin')->guest()){
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('admin/login');
            }
        }
        // 如果用户ID为(1)或后台首页路由,则不进行权限判断
//        if ($request->user('admin')->id != 1 && ! $request->user('admin')->can(\Route::currentRouteName()) && \Route::currentRouteName() != 'admin') {
//            return redirect(route('admin.403'));
//        }

        return $next($request);
    }
}
