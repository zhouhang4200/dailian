<?php

namespace App\Console\Commands;

use App\Models\UserAsset;
use App\Models\UserAssetFlow;
use App\Models\UserFinanceReportDay;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;

/**
 * 生成用户资金报表
 * 默认是当天跑前一天的数据,手动传入日期也一样
 * Class UserFinanceReportDay
 * @package App\Console\Commands
 */
class UserFinanceReportDayCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wz:user-report-day {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '用户资金日报表';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $date = $this->argument('date') ?? date('Y-m-d');

        $now = Carbon::parse($date);
        $yesterday = $now->subDay(1)->toDateString(); // 昨天
        $beforeYesterday = $now->copy()->subDays(1)->toDateString(); // 前天

        $usersAsset = UserAsset::all();
        foreach ($usersAsset as $item) {
            // 获取用户前一天的报表数据,提取余额,做为当前报表的期初金额
            $openingBalance = 0;
            $openingFrozen = 0;
            $beforeReport = UserFinanceReportDay::where('user_id', $item->user_id)
                ->where('date', $beforeYesterday)
                ->first();

            if (! is_null($beforeReport)) {
                $openingBalance = $beforeReport->balance;
                $openingFrozen = $beforeReport->frozen;
            }

            // 获取当前用户最后一条流水,取出余额与冻结金额
            $userBalanceAndFrozen = UserAssetFlow::where('user_id', $item->id)
                ->where('date', $yesterday)
                ->orderBy('id', 'desc')
                ->first();

            // 获取充值 提现 支出 收入 金额
            $userRechargeAndWithdrawAndExpendAndIncome = UserAssetFlow::selectRaw('type, sum(amount) as amount')
                ->where('user_id', $item->user_id)
                ->where('date', $yesterday)
                ->whereIn('type', [1, 2, 5, 6])
                ->groupBy('type')
                ->pluck('amount', 'type');

            // 写入报表
            UserFinanceReportDay::create([
                'user_id' => $item->user_id,
                'opening_balance' => $openingBalance,
                'opening_frozen' => abs($openingFrozen),
                'balance' => $userBalanceAndFrozen->balance ?? 0,
                'frozen' => abs($userBalanceAndFrozen->frozen ?? 0),
                'recharge' => $userRechargeAndWithdrawAndExpendAndIncome[1] ?? 0,
                'withdraw' => abs($userRechargeAndWithdrawAndExpendAndIncome[2] ?? 0),
                'income' => $userRechargeAndWithdrawAndExpendAndIncome[5] ?? 0,
                'expend' => abs($userRechargeAndWithdrawAndExpendAndIncome[6] ?? 0),
                'date' => $yesterday,
            ]);
        }
    }
}
