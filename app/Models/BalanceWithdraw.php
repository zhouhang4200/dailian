<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

        if (isset($filters['trade_no']) && ! empty($filters['trade_no'])) {
            $query->where('trade_no', $filters['trade_no']);
        }

        if (isset($filters['user_id']) && ! empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (isset($filters['remark']) && ! empty($filters['remark'])) {
            $query->whereHas('userAssetFlow', function ($query) use ($filters) {
                $query->where('remark', $filters['remark']);
            });
        }

        if (isset($filters['startDate']) && ! empty($filters['startDate'])) {
            $query->where('startDate', '>=', $filters['startDate']);
        }

        if (isset($filters['endDate']) && ! empty($filters['endDate'])) {
            $query->where('endDate', '<=', $filters['endDate']);
        }
        return $query;
    }
}
