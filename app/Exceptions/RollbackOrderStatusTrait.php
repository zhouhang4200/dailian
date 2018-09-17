<?php

namespace App\Exceptions;

use App\Models\GameLevelingOrder;

/**
 * 出现异常后回滚es中的订单状态
 * Trait RollbackOrderStatusTrait
 * @package App\Exceptions
 */
trait RollbackOrderStatusTrait
{
    /**
     * @param $par
     */
    public function rollbackOrderStatus($par)
    {
        if (isset($par['trade_no'])) {

            $order = GameLevelingOrder::where('trade_no', $par['trade_no'])->first();
            $newOrder = clone $order;
            $newOrder->status = $order->status;
            $newOrder->updated_at = date('Y-m-d H:i:s');
            myLog('order', [$order, $newOrder->save()]);
        }
    }
}