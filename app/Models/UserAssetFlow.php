<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * 用户资产
 * Class UserAssetFlow
 * @package App\Models
 */
class UserAssetFlow extends Model
{
    public $fillable = [
        'user_id',
        'type',
        'sub_type',
        'trade_no',
        'amount',
        'balance',
        'frozen',
        'date',
        'remark' ,
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function balanceWithdraw()
    {
        return $this->hasOne(BalanceWithdraw::class, 'trade_no', 'trade_no');
    }

    /**
     * 根据传入条件过滤
     * @param $query
     * @param $condition
     *
     * @return mixed
     */
    public static function scopeCondition($query, $condition)
    {

        if (isset($condition['user_id']) && $condition['user_id']) {
            $query->where('user_id', $condition['user_id']);
        }

        if (isset($condition['type']) && $condition['type']) {
            $query->where('type', $condition['type']);
        }

        if (isset($condition['sub_type']) && $condition['sub_type']) {
            $query->where('sub_type', $condition['sub_type']);
        }

        if (isset($condition['trade_no']) && $condition['trade_no']) {
            $query->where('trade_no', $condition['trade_no']);
        }

        if (isset($condition['start_time']) && $condition['start_time']) {
            $query->where('created_at', '>=',$condition['start_time']);
        }

        if (isset($condition['end_time']) && $condition['end_time']) {
            $query->where('created_at', '<=', $condition['end_time'] . ' 23:59:59');
        }

        return $query;
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
