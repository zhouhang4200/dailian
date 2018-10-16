<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RealNameCertification extends Model
{
    protected $fillable = [
        'user_id',
        'real_name',
        'identity_card',
        'identity_card_front',
        'identity_card_back',
        'bank_card',
        'bank_name',
        'status',
        'alipay_account',
        'remark',
        'created_at',
        'updated_at',
        'identity_card_hand'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @param $query
     * @param array $filters
     * @return mixed
     */
    public static function scopeFilter($query, $filters = [])
    {
        if ($filters['name']) {
            $query->whereHas('user', function ($query) use ($filters) {
                $query->where('name', $filters['name']);
            });
        }

        if ($filters['status']) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['startDate'])) {
            $query->where('created_at', '>=', $filters['startDate']);
        }

        if (isset($filters['endDate'])) {
            $query->where('updated_at', '<=', $filters['endDate'].' 23:59:59');
        }

        return $query;
    }
}
