<?php

namespace App\Exceptions\Order;

use Exception;

/**
 * 订单异常
 * Class OrderException
 * @package App\Exceptions\Order
 */
class OrderException extends Exception
{
    /**
     * OrderException constructor.
     * @param $message
     * @param int $code
     * @throws Exception
     */
    public function __construct($message, $code = 0)
    {
        myLog('order-ex', [$message, self::getFile(), self::getFile(), self::getMessage()]);
        parent::__construct($message, $code);
    }
}