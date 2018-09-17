<?php

namespace App\Exceptions;

use Exception;

/**
 * 用户资金异常
 * Class UserAssetException
 * @package App\Exceptions\UserAsset
 */
class UserAssetException extends Exception
{
    /**
     * UserAssetException constructor.
     * @param $message
     * @param int $code
     * @throws Exception
     */
    public function __construct($message, $code = 0)
    {
        myLog('user-asset-ex', [
            '用户' => optional(auth()->user())->id,
            '错误' => $message,
            '文件' => self::getFile(),
            '行号' => self::getLine(),
            '入参' => request()->all()
        ]);
        parent::__construct($message, $code);
    }
}
