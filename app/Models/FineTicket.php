<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class FineTicket
 * @package App\Models
 */
class FineTicket extends Model
{
    public $fillable = [
        'user_id',
        'trade_no',
        'relation_trade_no',
        'amount',
        'remark',
        'reason',
    ];

    /**
     * @param $query
     * @param $condition
     * @return mixed
     */
    public static function scopeCondition($query, $condition)
    {
        if (isset($condition['user_id']) && $condition['user_id']) {
            $query->where('user_id', $condition['user_id']);
        }
        if (isset($condition['trade_no']) && $condition['trade_no']) {
            $query->where('trade_no', $condition['trade_no']);
        }
        if (isset($condition['status']) && $condition['status']) {
            $query->where('status', $condition['status']);
        }
        if (isset($condition['start_time']) && $condition['start_time']) {

        }
        return $query;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userAssetFlows()
    {
        return $this->hasMany(UserAsset::class, 'trade_no', 'trade_no');
    }
}
