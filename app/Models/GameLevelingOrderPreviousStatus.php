<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public static function getLatestBy($gameLevelingOrderTradeNO)
    {
        return self::where('game_leveling_order_trade_no', $gameLevelingOrderTradeNO)->latest('id')->first();
    }
}
