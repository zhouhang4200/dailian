<?php

namespace App\Http\Middleware;

use Closure;

/**
 * 检测账户是否有通过实名认证
 * Class CheckRealNameCertification
 * @package App\Http\Middleware
 */
class CheckRealNameCertification
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
        if ($request->user()->realNameCertification->status == 3) {
            // 如果是 AJAX 请求，则通过 JSON 返回
            if ($request->expectsJson()) {
                return response()->json(['message' => '您账号需要实名认证'], 400);
            }
            return redirect(route('real_name_certification'));
        }
        return $next($request);
    }
}
