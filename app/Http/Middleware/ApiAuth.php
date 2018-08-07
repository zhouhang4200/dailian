<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;

class ApiAuth
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
        // 检测 app_id, sign
        if (! isset($request->app_id)) {
            return response()->apiFail('app_id缺失');
        }

        if (! isset($request->sign)) {
            return response()->apiFail('sign缺失');
        }

        $request->user = User::where('app_id', $request->app_id)->first();

        if (! $request->user) {
            return response()->apiFail('app_id不正确');
        }

        // 检测 sign
        $par = $request->all();
        ksort($par);
        $str = '';
        foreach ($par  as $key => $value) {
            if (trim($key) != 'sign') {
                $str .= $key . '=' . $value . '&';
            }
        }

        $sign = md5(rtrim($str,  '&') . $request->user->app_secret);

        if ($sign != $request->sign) {
            return response()->apiFail('您的签名不正确');
        }
        return $next($request);
    }
}
