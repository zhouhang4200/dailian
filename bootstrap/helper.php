<?php

/**
 * 生成订单号
 * @return string
 */
function generateOrderNo()
{
    // 14位长度当前的时间 20150709105750
    $orderDate = date('YmdHis');
    // 今日订单数量
    $orderQuantity = cache()->increment(config('redis_key.order.quantity') . date('Ymd'));
    return $orderDate . str_pad($orderQuantity, 8, 0, STR_PAD_LEFT);
}