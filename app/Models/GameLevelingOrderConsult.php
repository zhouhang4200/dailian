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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'take_user_id');
    }

    /**
     * 获取撤销发起人
     * @return int 0 不存在撤销 1 撤销发起人为 发单方
     */
    public function getConsultInitiator()
    {
        return (int) $this->status == 3 ? 0 : $this->initiator;
    }

    /**
     * 获取订单撤销 描述
     * @return string
     */
    public function getConsultDescribe()
    {
        if ( $this->status != 2) {

            if ($this->initiator == 1) { // 如果发起人为发单方

                // 当前用户父Id 等于撤销发起人
                if ($this->parent_user_id == request()->user()->parent_id) {
                    return sprintf("您发起撤销, <br/> 你支付代练费用 %.2f 元, 对方支付保证金 %.2f, <br/> 原因: %s",
                        $this->amount,
                        bcadd($this->security_deposit, $this->efficiency_deposit),
                        $this->reason
                    );
                } else {
                    return sprintf("对方发起撤销, <br/> 对方支付代练费用 %.2f 元, 你方支付保证金 %.2f, <br/> 原因: %s",
                        $this->amount,
                        bcadd($this->security_deposit, $this->efficiency_deposit),
                        $this->reason
                    );
                }
            } else if ($this->initiator == 2) {  // 如果发起人为接单方

                if ($this->parent_user_id == request()->user()->parent_id) {
                    return sprintf("您发起撤销, <br/> 对方支付代练费用 %.2f 元, 你支付保证金 %.2f, <br/> 原因: %s",
                        $this->amount,
                        bcadd($this->security_deposit, $this->efficiency_deposit),
                        $this->reason
                    );
                } else {
                    return sprintf("对方发起撤销, <br/> 对方支付代练费用 %.2f 元, 您支付保证金 %.2f, <br/> 原因: %s",
                        $this->amount,
                        bcadd($this->security_deposit, $this->efficiency_deposit),
                        $this->reason
                    );
                }
            }

        } else {
            return '';
        }
    }
}
