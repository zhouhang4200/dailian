<?php

namespace App\Http\Middleware;

use Closure;

/**
 * Class CheckApiToken
 * @package App\Http\Middleware
 */
class CheckApiToken
{
    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->guard('api')->check()) {
            auth()->shouldUse('api');
            return $next($request);
        } else {
            return response()->apiJson(1004);
        }
    }
}
