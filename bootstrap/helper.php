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

if (!function_exists('hasEmployees')) {
    /**
     * 获取某个岗位有哪些员工
     * @param string $prefix
     * @return string
     */
    function hasEmployees($userRole)
    {
        $userNames = $userRole->users ? $userRole->users->pluck('name')->toArray() : '';

        if ($userNames) {
            return implode($userNames, '、');
        }
        return '';
    }
}