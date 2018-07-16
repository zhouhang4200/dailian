<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameLevelingOrder extends Model
{
    public $fillable = [
        'source',
        'trade_no',
        'user_id',
        'parent_user_id',
        'order_type_id',
        'game_type_id',
        'game_class_id',
        'game_id',
        'game_name',
        'region_id',
        'region_name',
        'server_id',
        'server_name',
        'game_leveling_type_id',
        'game_leveling_type_name',
        'title',
        'amount',
        'game_account',
        'game_password',
        'game_role',
        'security_deposit',
        'efficiency_deposit',
        'explain',
        'requirement',
        'take_order_password',
        'player_phone',
        'player_qq',
        'user_qq',
        'user_phone',
    ];

    /**
     * 订单状态说明
     * @var array
     */
    public $statusDes = [
        1 => '未接单',
        2 => '代练中',
        3 => '待验收',
        4 => '撤销中',
        5 => '仲裁中',
        6 => '仲裁中',
        7 => '异常',
        8 => '锁定',
        9 => '已撤销',
        10 => '已仲裁',
        11 => '已结算',
        12 => '强制撤销',
        13 => '已下架',
        14 => '已撤单',
    ];

    /**
     * 按订单号查询订单信息
     * @param $tradeNO
     * @return mixed
     */
    public static function getOrderBy($tradeNO)
    {
        return self::where('trade_no', $tradeNO)->with(['complain', 'consult'])->first();
    }

    /**
     * 根据传入的条件获取订单
     * @param  integer $who 1 发单方 2 接单方 3 不限定
     * @param  array $condition 传入的过滤条件
     * @return $this|\Illuminate\Database\Eloquent\Builder|static
     */
    public static function getOrderByCondition($condition, $who = 3)
    {
        if ($who == 1) {
            $build = self::with('complain', 'consult')->where('parent_user_id', request()->user()->parent_id);
        } else if ($who == 2) {
            $build = self::with('complain', 'consult')->where('take_parent_user_id', request()->user()->parent_id);
        } else {
            $build = self::with('complain', 'consult');
        }

        if (isset($condition['parent_user_id']) && $condition['parent_user_id']) {
            $build->where('parent_user_id', $condition['parent_user_id']);
        }

        if (isset($condition['take_parent_user_id']) && $condition['take_parent_user_id']) {
            $build->where('take_parent_user_id', $condition['take_parent_user_id']);
        }

        if (isset($condition['status']) && $condition['status']) {
            $build->where('status', $condition['status']);
        }

        if (isset($condition['trade_no']) && $condition['trade_no']) {
            $build->where('trade_no', $condition['trade_no']);
        }

        if (isset($condition['game_id']) && $condition['game_id']) {
            $build->where('game_id', $condition['game_id']);
        }

        if (isset($condition['start_time']) && $condition['start_time']) {
            $build->where('created_at', '>=',$condition['start_time']);
        }

        if (isset($condition['end_time']) && $condition['end_time']) {
            $build->where('created_at', '>=',$condition['end_time']);
        }

        return $build;
    }

    /**
     * 获取订单状态
     * @return mixed
     */
    public  function getOrderStatusDes()
    {
        return $this->statusDes[$this->status];
    }

    /**
     * 关联仲裁表
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function complain()
    {
        return $this->hasOne(GameLevelingOrderConsult::class, 'game_leveling_orders_trade_no', 'trade_no');
    }

    /**
     * 关联协商撤销表
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function consult()
    {
        return $this->hasOne(GameLevelingOrderConsult::class, 'game_leveling_orders_trade_no', 'trade_no');
    }
}
