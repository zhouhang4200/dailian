<?php

namespace App\Exceptions;

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
        myLog('order-ex', [
            '用户' => optional(auth()->user())->id,
            '错误' => $message,
            '文件' => self::getFile(),
            '行号' => self::getLine(),
            '入参' => request()->all()
        ]);

        parent::__construct($message, $code);
    }
}