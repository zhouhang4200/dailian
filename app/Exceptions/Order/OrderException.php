<?php

namespace App\Exceptions\Order;

use Exception;

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

        myLog('order-ex', [$message, self::getFile()]);
        parent::__construct($message, $code);
    }
}