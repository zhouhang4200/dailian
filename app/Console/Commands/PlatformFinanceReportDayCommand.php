<?php

namespace App\Console\Commands;

use App\Models\UserFinanceReportDay;
use App\Models\PlatformFinanceReportDay;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

/**
 * 生成平台资金报表
 * 默认是当天跑前一天的数据,手动传入日期也一样
 * Class PlatformFinanceReportDayCommand
 * @package App\Console\Commands
 */
class PlatformFinanceReportDayCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'finance:platform-report-day {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '平台资金日报表';


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

        // 获取平台前一天的报表数据,提取余额/冻结金额,做为当前报表的期初余额/冻结
        $openingBalance = 0;
        $openingFrozen = 0;
        $beforeReport = PlatformFinanceReportDay::where('date', $beforeYesterday)->first();

        if (! is_null($beforeReport)) {
            $openingBalance = $beforeReport->balance;
            $openingFrozen = $beforeReport->frozen;
        }

        // 获取当天报表中所有用户的 余额 冻结 充值 提现 支出 收入 金额
        $userBalanceFrozenRechargeAndWithdrawAndExpendAndIncome = UserFinanceReportDay::selectRaw('sum(balance) as balance , sum(frozen) as frozen , sum(withdraw) as withdraw , sum(recharge) as  recharge, sum(expend) as expend, sum(income) as income')
            ->where('date', $yesterday)
            ->first();

        // 写入报表
        PlatformFinanceReportDay::create([
            'opening_balance' => $openingBalance,
            'opening_frozen' => abs($openingFrozen),
            'balance' => $userBalanceFrozenRechargeAndWithdrawAndExpendAndIncome->balance ?? 0,
            'frozen' => $userBalanceFrozenRechargeAndWithdrawAndExpendAndIncome->frozen ?? 0,
            'recharge' => $userBalanceFrozenRechargeAndWithdrawAndExpendAndIncome->recharge ?? 0,
            'withdraw' => abs($userBalanceFrozenRechargeAndWithdrawAndExpendAndIncome->withdraw ?? 0),
            'income' => $userBalanceFrozenRechargeAndWithdrawAndExpendAndIncome->income ?? 0,
            'expend' => abs($userBalanceFrozenRechargeAndWithdrawAndExpendAndIncome->expend ?? 0),
            'date' => $yesterday,
        ]);

    }
}
