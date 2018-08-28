<?php

namespace App\Http\Middleware;

use Closure;

class CheckApiSign
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        myLog('api-request', request()->all());

        // 在生产环境才做签名检测
        if (app()->environment() == 'production') {

            $data = [];
            // 检测 sign
            if (!isset($request->sign) || !isset($request->timestamp)) {
                return response()->apiJson(1001);
            }

            $par = $request->all();

            ksort($par);
            $str = '';
            foreach ($par as $key => $value) {
                if (!in_array($key, ['sign', 'avatar', 'image', 'image_1', 'image_2', 'image_3'])) {
                    $str .= $key . '=' . $value . '&';
                }
            }

            $sign = md5(rtrim($str, '&') . 'ajJKDej2jF');

            if ($sign != $request->sign) {
                return response()->apiJson(1002);

            }

        }
        return $next($request);
    }
}
