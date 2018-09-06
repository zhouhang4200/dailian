<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatistic extends Model
{
    public $fillable = [
        'date',
        'trade_no',
        'status',
        'game_id',
        'game_name',
        'consult_creator',
        'complain_creator',
        'user_id',
        'parent_user_id',
        'take_user_id',
        'take_parent_user_id',
        'amount',
        'security_deposit',
        'efficiency_deposit',
        'consult_complain_amount',
        'consult_complain_deposit',
        'poundage',
        'take_poundage',
        'fine',
        'take_fine',
        'order_created_at',
        'order_finished_at',
        'third',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gameLevelingOrder()
    {
        return $this->belongsTo(GameLevelingOrder::class, 'trade_no', 'trade_no');
    }

    /**
     *  前台查找
     * @param $query
     * @param $filters
     * @return mixed
     */
    public static function scopeFilter($query, $filters)
    {
        if ($filters['startDate']) {
            $query->where('date', '>=', $filters['startDate']);
        }

        if ($filters['endDate']) {
            $query->where('date', '<=', $filters['endDate']);
        }

        return $query;
    }

    /**
     *  后台查找
     * @param $query
     * @param $filters
     * @return mixed
     */
    public static function scopeAdminFilter($query, $filters)
    {
        if ($filters['startDate']) {
            $query->where('date', '>=', $filters['startDate']);
        }

        if ($filters['endDate']) {
            $query->where('date', '<=', $filters['endDate']);
        }

        if ($filters['gameId']) {
            $query->where('game_id', $filters['gameId']);
        }

        if ($filters['takeUserId']) {
            $takeParentUserId = User::find($filters['takeUserId'])->parent_id;

            $query->where('take_parent_user_id', $takeParentUserId);
        }

        return $query;
    }
}
