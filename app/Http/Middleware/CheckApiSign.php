<?php

namespace App\Http\Middleware;

use Closure;

/**
 * 检查接口传过来的身份信息，验证签名（发单器对接丸子接口用到）
 * Class CheckApiSign
 * @package App\Http\Middleware
 */
class CheckApiSign
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     * @throws \Exception
     */
    public function handle($request, Closure $next)
    {
        myLog('api-request', array_merge(['uri' => request()->getUri()], request()->all()));

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
                if (!in_array($key, ['sign', 'image'])) {
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
