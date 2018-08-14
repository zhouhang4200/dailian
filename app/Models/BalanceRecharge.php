<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 余额充值
 * Class BalanceRecharge
 *
 * @package App\Models
 */
class BalanceRecharge extends Model
{
    public $fillable = [
      'user_id',
      'amount',
      'trade_no',
      'source',
      'remark',
    ];

    /**
     *
     * @param $query
     * @param $conditions
     * @return mixed
     */
    public static function scopeCondition($query, $conditions)
    {
        if (isset($conditions['user_id']) && $conditions['user_id']) {
            $query->where('user_id', $conditions['user_id']);
        }
        if (isset($conditions['source']) && $conditions['source']) {
            $query->where('source', $conditions['source']);
        }
        if (isset($conditions['start_time'])) {
            $query->where('created_at', '>=', $conditions['start_time']);
        }
        if (isset($conditions['end_time'])) {
            $query->$conditions('updated_at', '<=', $conditions['end_time'].' 23:59:59');
        }
        return $query;
    }
}
