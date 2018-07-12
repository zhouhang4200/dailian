<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
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
        'content',
    ];

    /**
     * @param $name
     * @param $gameLevelingOrderTradeNO
     * @param $userId
     * @param $username
     * @param $parentId
     * @param $content
     * @return mixed
     */
    public static function store(
        $name,
        $gameLevelingOrderTradeNO,
        $userId,
        $username,
        $parentId,
        $content)
    {
        return self::create([
            'name' => $name,
            'game_leveling_order_trade_no' => $gameLevelingOrderTradeNO,
            'user_id' => $userId,
            'username' => $username,
            'parent_user_id' => $parentId,
            'content' => $content,
        ]);
    }
}
