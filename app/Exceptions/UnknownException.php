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

        parent::__construct($message, $code);
        myLog('unknown-ex', [
            '用户' => optional(auth()->user())->id,
            '错误' => $message,
            '文件' => self::getFile(),
            '行号' => self::getLine(),
            '入参' => request()->all()
        ]);
    }
}
