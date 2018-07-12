<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class GameLevelingOrderConsult
 * @package App\Models
 */
class GameLevelingOrderConsult extends Model
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
     * @param $amount
     * @param $securityDeposit
     * @param $efficiencyDeposit
     * @param $remark
     * @return mixed
     */
    public static function store(
        $userId,
        $parentUserId,
        $gameLevelingOrderTradeNO,
        $amount,
        $securityDeposit,
        $efficiencyDeposit,
        $remark)
    {
        return self::create([
            'user_id' => $userId,
            'parent_user_id' => $parentUserId,
            'game_leveling_orders_trade_no' => $gameLevelingOrderTradeNO,
            'amount' => $amount,
            'security_deposit' => $securityDeposit,
            'efficiency_deposit' => $efficiencyDeposit,
            'remark' => $remark,
        ]);
    }
}
