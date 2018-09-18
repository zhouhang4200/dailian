<?php

namespace App\Http\Middleware;

use App\Models\GameLevelingOrder;
use Closure;

/**
 * 后置中间件
 * Class SyncESOrderStatus
 * @package App\Http\Middleware
 */
class SyncESOrderStatus
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
        $response = $next($request);

        // 执行操作
        if (isset($request->trade_no)) {
            // 如果输入参数存在订单号则进行es订单同步
            $order = GameLevelingOrder::where('trade_no', $request->trade_no)->first();
            $order->status = $order->status;
            $order->save();
        }

        return $response;
    }
}
