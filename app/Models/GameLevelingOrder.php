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
        3 => '待收验',
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
     * 获取订单状态
     * @return mixed
     */
    public  function getOrderStatus()
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
