<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 代练订单操作日志
 * Class GameLevelingOrderLog
 * @package App\Models
 */
class GameLevelingOrderLog extends Model
{
    public $fillable = [
        'name',
        'game_leveling_order_trade_no',
        'user_id',
        'username',
        'parent_user_id',
        'description',
    ];

    /**
     * @param $name
     * @param $gameLevelingOrderTradeNO
     * @param $userId
     * @param $username
     * @param $parentId
     * @param $description
     * @return mixed
     */
    public static function store(
        $name,
        $gameLevelingOrderTradeNO,
        $userId,
        $username,
        $parentId,
        $description)
    {
        return self::create([
            'name' => $name,
            'game_leveling_order_trade_no' => $gameLevelingOrderTradeNO,
            'user_id' => $userId,
            'username' => $username,
            'parent_user_id' => $parentId,
            'description' => $description,
        ]);
    }
}
