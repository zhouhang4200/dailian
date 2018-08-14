<?php

namespace App\Http\Controllers\Front\Finance;

use DB;
use App\Exceptions\UserAsset\UserAssetBalanceException;
use App\Models\BalanceWithdraw;
use App\Http\Controllers\Controller;
use App\Services\UserAssetService;

class BalanceWithdrawController extends Controller
{
    public function index()
    {
        return view('front.finance.balance-withdraw.index');
    }

    /**
     * 提现
     * @return mixed
     * @throws \Exception
     */
    public function store()
    {
        if (optional(auth()->user()->parent->realNameCertification)->status != 2) {
            return response()->ajaxFail('您的账号没有进行实名认证,不能进行提现');
        }

        if (! \Hash::check(clientRSADecrypt(request('password')), auth()->user()->pay_password)) {
            return response()->ajaxFail('支付密码错误');
        }
        // 计算手续费
        $poundage = bcmul(request('amount'), 0.01);
        // 实际到账金额
        $amount = bcsub(request('amount'), $poundage);

        DB::beginTransaction();
        try {
            // 创建提现记录
            $record = BalanceWithdraw::create([
                'user_id' => auth()->user()->parent_id,
                'trade_no' => generateOrderNo(),
                'real_amount' => $amount,
                'amount' => request('amount'),
                'poundage' => $poundage,
                'real_name' => auth()->user()->parent->realNameCertification->real_name,
                'bank_card' => auth()->user()->parent->realNameCertification->bank_card,
                'bank_name' => auth()->user()->parent->realNameCertification->bank_name,
                'status' => 1
            ]);
            // 冻结资金
            UserAssetService::init(35, auth()->user()->parent_id, $amount, $record->trade_no)->frozen();
        } catch (UserAssetBalanceException $balanceException) {
            return response()->ajaxFail('您的账号余额不够');
        } catch (\Exception $exception) {
            return response()->ajaxFail($exception->getMessage());
        }
        DB::commit();
        return response()->ajaxSuccess('我们已收到您的提现请求，会尽快处理');
    }

    /**
     * 提现记录
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function record()
    {
        return view('front.finance.balance-withdraw.record', [
            'withdraws' => BalanceWithdraw::condition(array_merge(request()->except('user_id'), [
                'user_id' => request()->user()->parent_id]))->paginate(),
        ]);
    }

    /**
     * 提现导出
     */
    public function export()
    {
        $query = BalanceWithdraw::condition(array_merge(request()->except('user_id'), [
            'user_id' => request()->user()->parent_id]));

        return export([
            '提现单号',
            '提现金额',
            '状态',
            '备注',
            '创建时间',
        ], '我的提现', $query, function ($query, $out){
            $query->chunk(100, function ($items) use ($out) {
                foreach ($items as $item) {
                    $data = [
                        $item->trade_no . "\t",
                        $item->amount,
                        config('user_asset.withdraw_status')[$item->status],
                        $item->remark,
                        $item->created_at,
                    ];
                    fputcsv($out, $data);
                }
            });
        });
    }
}
