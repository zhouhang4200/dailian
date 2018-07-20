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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userAssetFlow()
    {
        return $this->belongsTo(UserAssetFlow::class, 'trade_no', 'trade_no');
    }

    /**
     * @param $query
     * @param array $filters
     * @return mixed
     */
    public static function scopeFilter($query, $filters = [])
    {
        if (isset($filters['type']) && ! empty($filters['type'])) {
            $query->whereHas('userAssetFlow', function ($query) use ($filters) {
                $query->where('type', $filters['type']);
            });
        }

        if (isset($filters['status']) && ! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['tradeNo']) && ! empty($filters['tradeNo'])) {
            $query->where('trade_no', $filters['tradeNo']);
        }

        if (isset($filters['userId']) && ! empty($filters['userId'])) {
            $query->where('user_id', $filters['userId']);
        }

        if (isset($filters['remark']) && ! empty($filters['remark'])) {
            $query->whereHas('userAssetFlow', function ($query) use ($filters) {
                $query->where('remark', $filters['remark']);
            });
        }

        if (isset($filters['startDate']) && ! empty($filters['startDate'])) {
            $query->where('created_at', '>=', $filters['startDate']);
        }

        if (isset($filters['endDate']) && ! empty($filters['endDate'])) {
            $query->where('updated_at', '<=', $filters['endDate'].' 23:59:59');
        }
        return $query;
    }
}
