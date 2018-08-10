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

        myLog('receive-datas', ['datas' => $request->all()]);
        // 检测 sign
        $par = $request->all();
        ksort($par);
        $str = '';
        foreach ($par  as $key => $value) {
            if (! in_array($key, ['sign', 'pic1', 'pic2', 'pic3', 'image'])) {
                $str .= $key . '=' . $value . '&';
            }
        }

        $sign = md5(rtrim($str,  '&') . $request->user->app_secret);
//        myLog('sign', ['sign' => $sign, 'str' => $str]);
        if ($sign != $request->sign) {
            return response()->apiFail('您的签名不正确');
        }
        return $next($request);
    }
}
