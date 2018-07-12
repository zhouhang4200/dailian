<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 仲裁记录
 * Class GameLevelingOrderComplain
 * @package App\Models
 */
class GameLevelingOrderComplain extends Model
{
    public $fillable = [
        'user_id',
        'parent_user_id',
        'game_leveling_orders_trade_no',
        'amount',
        'security_deposit',
        'efficiency_deposit',
        'remark',
    ];

    /**
     * @param $userId
     * @param $parentUserId
     * @param $gameLevelingOrderTradeNO
     * @param $remark
     * @return mixed
     */
    public static function store(
        $userId,
        $parentUserId,
        $gameLevelingOrderTradeNO,
        $remark)
    {
        return self::create([
            'user_id' => $userId,
            'parent_user_id' => $parentUserId,
            'game_leveling_orders_trade_no' => $gameLevelingOrderTradeNO,
            'remark' => $remark,
        ]);
    }
}
