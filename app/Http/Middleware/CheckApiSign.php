<?php

namespace App\Http\Middleware;

use Closure;

class CheckApiSign
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
        // 在生产环境才做签名检测
        if (app()->environment() == 'production') {
            $data = [];
            // 检测 sign
            if (! isset($request->sign) || ! isset($request->timestamp)) {
                $data = config('api.code')['1001'];
                $data['data'] = [];
                return response()->json($data);
            }

            $par = $request->all();

            ksort($par);
            $str = '';
            foreach ($par as $key => $value) {
                if (! in_array($key, ['sign', 'avatar', 'image', 'image_1', 'image_2', 'image_3'])) {
                    $str .= $key . '=' . $value . '&';
                }
            }

            $sign = md5(rtrim($str,  '&') . 'ajJKDej2jF');

            if ($sign != $request->sign) {
                $data = config('api.code')['1002'];
                $data['data'] = [];
                return response()->json($data);
            }
        }
        return $next($request);
    }
}
