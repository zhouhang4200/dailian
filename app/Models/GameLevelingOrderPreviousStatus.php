<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 订单前一个状态
 * Class GameLevelingOrderPreviousStatus
 *
 * @package App\Models
 */
class GameLevelingOrderPreviousStatus extends Model
{
    public $fillable = [
        'status',
        'game_leveling_order_trade_no',
    ];

    /**
     * @param $gameLevelingOrderTradeNO
     * @param $status
     */
    public static function store($gameLevelingOrderTradeNO, $status)
    {
        self::create([
            'status' => $status,
            'game_leveling_order_trade_no' => $gameLevelingOrderTradeNO,
        ]);
    }

    /**
     * @param $gameLevelingOrderTradeNO
     * @return mixed
     */
    public static function getLatestBy($gameLevelingOrderTradeNO)
    {
        $previous = self::where('game_leveling_order_trade_no', $gameLevelingOrderTradeNO)->latest('id')->first();
        $previous->delete();
        return $previous->status;
    }
}
