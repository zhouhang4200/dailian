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
        'initiator',
        'user_id',
        'parent_user_id',
        'game_leveling_order_trade_no',
        'amount',
        'security_deposit',
        'efficiency_deposit',
        'status',
        'reason',
    ];

    /**
     * @param $initiator
     * @param $userId
     * @param $parentUserId
     * @param $gameLevelingOrderTradeNO
     * @param $amount
     * @param $securityDeposit
     * @param $efficiencyDeposit
     * @param $status
     * @param $reason
     * @return mixed
     */
    public static function store(
        $initiator,
        $userId,
        $parentUserId,
        $gameLevelingOrderTradeNO,
        $amount,
        $securityDeposit,
        $efficiencyDeposit,
        $status,
        $reason)
    {
        return self::updateOrCreate(['game_leveling_order_trade_no' => $gameLevelingOrderTradeNO], [
            'initiator' => $initiator,
            'user_id' => $userId,
            'parent_user_id' => $parentUserId,
            'game_leveling_order_trade_no' => $gameLevelingOrderTradeNO,
            'amount' => $amount,
            'security_deposit' => $securityDeposit,
            'efficiency_deposit' => $efficiencyDeposit,
            'status' => $status,
            'reason' => $reason,
        ]);
    }
}
