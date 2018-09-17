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
        if (isset($par['trade_no']) && strlen($par['trade_no']) == 22) {
            $order = GameLevelingOrder::where('trade_no', $par['trade_no'])->first();
            $order->status = $order->status;
            $order->updated_at = date('Y-m-d H:i:s');
            $order->save();
        }
    }
}