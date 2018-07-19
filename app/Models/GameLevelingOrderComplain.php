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
     * @param $status
     * @param $reason
     * @return mixed
     */
    public static function store(
        $initiator,
        $userId,
        $parentUserId,
        $gameLevelingOrderTradeNO,
        $status,
        $reason)
    {
        return self::updateOrCreate(['game_leveling_order_trade_no' => $gameLevelingOrderTradeNO], [
            'initiator' => $initiator,
            'user_id' => $userId,
            'parent_user_id' => $parentUserId,
            'game_leveling_order_trade_no' => $gameLevelingOrderTradeNO,
            'status' => $status,
            'reason' => $reason,
        ]);
    }

    /**
     * 对应的仲裁图片
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function image()
    {
        return $this->morphMany(Attachment::class, 'attachment', 'attachment_type', 'trade_no', 'game_leveling_order_trade_no');
    }
}
