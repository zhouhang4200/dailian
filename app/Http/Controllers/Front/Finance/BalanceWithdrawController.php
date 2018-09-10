<?php

namespace App\Http\Controllers\Front\Finance;

use App\Exceptions\UserException;
use App\Services\UserBalanceService;
use DB;
use App\Exceptions\UserAssetException;
use App\Models\BalanceWithdraw;
use App\Http\Controllers\Controller;
use App\Services\UserAssetService;

/**
 * Class BalanceWithdrawController
 * @package App\Http\Controllers\Front\Finance
 */
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
        DB::beginTransaction();
        try {
            // 生成提现单
            $record = UserBalanceService::withdraw(
                request('amount'),
                auth()->user()->parent,
                request('alipay_account'),
                request('alipay_name')
            );
            // 冻结资金
            UserAssetService::init(35, $record->user_id, $record->amount, $record->trade_no)->frozen();
        } catch (UserException $exception) {
            return response()->ajaxFail($exception->getMessage(), [], $exception->getCode());
        }  catch (UserAssetException $exception) {
            return response()->ajaxFail($exception->getMessage(), [], $exception->getCode());
        }  catch (\Exception $exception) {
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
