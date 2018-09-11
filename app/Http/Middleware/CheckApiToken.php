<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate;

/**
 * Class CheckApiToken
 * @package App\Http\Middleware
 */
class CheckApiToken  extends Authenticate
{
//    /**
//     * @param $request
//     * @param Closure $next
//     * @return mixed
//     */
//    public function handle($request, Closure $next)
//    {
//        if (auth()->guard('api')->check()) {
//            auth()->shouldUse('api');
//            return $next($request);
//        } else {
//            return response()->apiJson(1004);
//        }
//    }

    protected function authenticate(array $guards)
    {

        if ($this->auth->guard('api')->check()) {
            return $this->auth->shouldUse('api');
        } else {
            return response()->apiJson(1004);
        }
    }
}
