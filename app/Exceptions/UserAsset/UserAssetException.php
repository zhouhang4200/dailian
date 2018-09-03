<?php

namespace App\Exceptions\UserAsset;

use Exception;

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
        parent::__construct($message, $code);
        myLog('user-asset-ex', [$message, self::getFile(), self::getLine(), self::getMessage()]);
    }
}
