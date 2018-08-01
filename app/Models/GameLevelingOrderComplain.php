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
     * @param $query
     * @param $condition
     * @return mixed
     */
    public static function scopeCondition($query, $condition)
    {
        if (isset($condition['game_leveling_order_trade_no']) && $condition['game_leveling_order_trade_no']) {
            $query->where('game_leveling_order_trade_no', $condition['game_leveling_order_trade_no']);
        }
        if (isset($condition['parent_user_id']) && $condition['parent_user_id']) {
            $query->where('parent_user_id', $condition['parent_user_id']);
        }
        if (isset($condition['status']) && $condition['status']) {
            $query->where('status', $condition['status']);
        }
        if (isset($condition['start_time']) && $condition['start_time']) {
            $query->where('created_at', '>=', $condition['start_time']);
        }
        if (isset($condition['end_time']) && $condition['end_time']) {
            $query->where('created_at', '<=', $condition['end_time']);
        }
        return $query;
    }

    public function order()
    {
        return $this->hasOne(GameLevelingOrder::class, 'trade_no', 'game_leveling_order_trade_no');
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
