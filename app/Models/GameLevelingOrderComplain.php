<?php

namespace App\Models;

use Carbon\Carbon;
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

    /**
     * @return string
     */
    public function complaint()
    {
        if ($this->parent_user_id == $this->order->parent_user_id) {
            return $this->order->parent_username . ' / ' . $this->order->parent_user_id .  " (发单)";
        } else {
            return $this->order->take_parent_username . ' / ' . $this->order->take_parent_user_id .  " (接单)";
        }
    }

    /**
     * @return string
     */
    public function beComplaint()
    {
        if ($this->parent_user_id != $this->order->parent_user_id) {
            return $this->order->parent_username . ' / ' . $this->order->parent_user_id . " (发单)";
        } else {
            return $this->order->take_parent_username . ' / ' . $this->order->take_parent_user_id . " (接单)";
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
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

    /**
     * 关联留言表
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany(GameLevelingOrderMessage::class, 'game_leveling_order_trade_no', 'game_leveling_order_trade_no')
            ->whereRaw('game_leveling_order_messages.type = 1');
    }

    /**
     * 获取订单仲裁 描述
     * @return string
     */
    public function getComplainDescribe()
    {
        // 当前用户父Id 等于仲裁发起人
        if ($this->parent_user_id == request()->user()->parent_id) {
            return sprintf("你发起仲裁 <br/> 原因: %s",
                $this->reason
            );
        } else {
            return sprintf("对方发起仲裁 <br/> 原因: %s",
                $this->reason
            );
        }
    }

    /**
     * 仲裁结果
     * @return string
     */
    public function getComplainResult()
    {
        if ($this->initiator == 1) { // 如果发起人为发单方

            // 当前用户父Id 等于仲裁发起人
            if ($this->parent_user_id == request()->user()->parent_id) {
                return sprintf("客服进行了【仲裁】  <br/> 你支付代练费用 %.2f 元, 对方支付保证金 %.2f <br/> 仲裁说明： %s",
                    $this->amount,
                    bcadd($this->security_deposit, $this->efficiency_deposit),
                    $this->reason
                );
            } else {

                return sprintf("客服进行了【仲裁】  <br/> 你支付代练费用 %.2f 元, 对方支付保证金 %.2f <br/> 仲裁说明： %s",
                    $this->amount,
                    bcadd($this->security_deposit, $this->efficiency_deposit),
                    $this->reason
                );
            }
        } else if ($this->initiator == 2) {  // 如果发起人为接单方
            // 客服进行了【仲裁】【你（对方）支出代练费1.0元，对方（你）支出保证金0.0元。仲裁说明：经查证，双方协商退单，已判定】
            if ($this->parent_user_id == request()->user()->parent_id) {
                return sprintf("客服进行了【仲裁】 <br/> 对方支付代练费用 %.2f 元, 你支付保证金 %.2f <br/> 仲裁说明： %s",
                    $this->amount,
                    bcadd($this->security_deposit, $this->efficiency_deposit),
                    $this->reason
                );
            } else {
                return sprintf("客服进行了【仲裁】 <br/> 你支付代练费用 %.2f 元, 对方支付保证金 %.2f <br/> 仲裁说明： %s",
                    $this->amount,
                    bcadd($this->security_deposit, $this->efficiency_deposit),
                    $this->reason
                );
            }
        }
    }

    /**
     * @param $value
     * @return string
     */
    public function getCreatedAtAttribute($value)
    {
        return Carbon::createFromTimestamp(strtotime($value))->toDateTimeString();
    }
}
