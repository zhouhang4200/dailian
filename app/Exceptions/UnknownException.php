<?php

namespace App\Exceptions;

use Exception;

/**
 * 未知异常
 * Class UnknownException
 * @package App\Exceptions
 */
class UnknownException extends Exception
{
    /**
     * UnknownException constructor.
     * @param $message
     * @param int $code
     * @throws Exception
     */
    public function __construct($message, $code = 0)
    {
        myLog('unknown-ex', [$message, self::getFile(), self::getLine(), self::getMessage()]);
        parent::__construct($message, $code);
    }
}
