<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 余额提现
 * Class BalanceWithdraw
 *
 * @package App\Models
 */
class BalanceWithdraw extends Model
{
    public $fillable = [
        'user_id',
        'trade_no',
        'amount',
        'real_name',
        'bank_card',
        'bank_name' ,
        'status' ,
        'remark' ,
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userAssetFlows()
    {
        return $this->hasMany(UserAssetFlow::class, 'trade_no', 'trade_no');
    }

    /**
     * @param $query
     * @param $conditions
     * @return mixed
     */
    public static function scopeCondition($query, $conditions)
    {
        if (isset($conditions['user_id']) && ! empty($conditions['user_id'])) {
            $query->where('user_id', $conditions['user_id']);
        }
        if (isset($conditions['status']) && ! empty($conditions['status'])) {
            $query->where('status', $conditions['status']);
        }
        if (isset($conditions['trade_no']) && ! empty($conditions['trade_no'])) {
            $query->where('status', $conditions['trade_no']);
        }

        if (isset($conditions['start_time'])) {
            $query->where('created_at', '>=', $conditions['start_time']);
        }

        if (isset($conditions['end_time'])) {
            $query->$conditions('created_at', '<=', $conditions['end_time'].' 23:59:59');
        }
        return $query;
    }
}
