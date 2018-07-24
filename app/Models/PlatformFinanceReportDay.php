<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PlatformFinanceReportDay
 * @package App\Models
 */
class PlatformFinanceReportDay extends Model
{
    /**
     * @var array
     */
    public $fillable = [
        'opening_balance',
        'opening_frozen',
        'recharge',
        'withdraw',
        'balance',
        'frozen',
        'expend',
        'income',
        'date',
    ];
    /**
     * @param $query
     * @param $condition
     * @return mixed
     */
    public static function scopeCondition($query, $condition)
    {
        if (isset($condition['start_time']) && $condition['start_time']) {
            $query->where('date', '>=',$condition['start_time']);
        }

        if (isset($condition['end_time']) && $condition['end_time']) {
            $query->where('date', '<=',$condition['end_time']);
        }
        return $query;
    }

    /**
     * 获取期初余额 (期初余额 + 期初冻结)
     * @return string
     */
    public function getOpeningBalance()
    {
        // 期初余额 (期初余额 + 期初冻结)
        return bcadd($this->opening_balance, $this->opening_frozen);
    }

    /**
     * 获取理论结余 (期初金额 - 支出  + 收入)
     * @return string
     */
    public function getTheoryBalance()
    {
        // 收入
        $income = bcadd($this->recharge, $this->income);
        // 支出
        $expend = bcadd($this->withdraw, $this->expend);

        // 理论结余 (期初金额 - 支出  + 收入)
        return  bcadd(bcsub($this->getOpeningBalance(), $expend), $income);
    }

    /**
     * 获取实际结余 (余额 + 冻结)
     * @return string
     */
    public function getRealityBalance()
    {
        // 实际结余 (余额 + 冻结)
        return bcadd($this->balance, $this->frozen);
    }

    /**
     * @return string
     */
    public function getDifference()
    {
        // 差异 (实际结余 - 理论结余)
        return  bcsub($this->getRealityBalance(), $this->getTheoryBalance());
    }
}
