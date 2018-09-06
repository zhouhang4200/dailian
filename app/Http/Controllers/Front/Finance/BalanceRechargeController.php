<?php

namespace App\Http\Controllers\Front\Finance;

use DB;
use Exception;
use Yansongda\Pay\Pay;
use App\Events\NotificationEvent;
use App\Models\BalanceRecharge;
use App\Services\UserAssetService;
use App\Http\Controllers\Controller;

/**
 * Class RechargeController
 * @package App\Http\Controllers\Front\Finance
 */
class BalanceRechargeController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('front.finance.balance-recharge.index');
    }

    /**
     * 充值记录
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function record()
    {
        return view('front.finance.balance-recharge.record', [
            'recharges' => BalanceRecharge::condition(array_merge(request()->except('user_id'), [
                'user_id' => request()->user()->parent_id]))->paginate(),
        ]);
    }

    /**
     * 充值记录导出
     */
    public function export()
    {
        $query = BalanceRecharge::condition(array_merge(request()->except('user_id'), [
            'user_id' => request()->user()->parent_id]));

        return export([
            '充值单号',
            '充值金额',
            '充值方式',
            '充值时间',
        ], '充值记录', $query, function ($query, $out){
            $query->chunk(100, function ($items) use ($out) {
                foreach ($items as $item) {
                    $data = [
                        $item->trade_no . "\t",
                        $item->amount,
                        config('user_asset.recharge_source')[$item->source],
                        $item->created_at,
                    ];
                    fputcsv($out, $data);
                }
            });
        });
    }

    /**
     * 扫码支付页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function pay()
    {
        // 生成充值单号
        $tradeNo = generateOrderNo();

        DB::beginTransaction();

        BalanceRecharge::create([
            'user_id' => request()->user()->parent_id,
            'amount' => request('amount'),
            'trade_no' => $tradeNo,
            'source' => request('source'),
        ]);

        $result = null;
        if (request('source') == 1) { // 支付宝
            $order = [
                'out_trade_no' => $tradeNo,
                'total_amount' => request('amount'),
                'subject' => '丸子代练-账户余额充值',
            ];
            $result = Pay::alipay(array_merge(config('pay.alipay'), [
                'notify_url' => route('finance.balance-recharge.alipay-notify')
            ]))->scan($order);

        } else { // 微信
            $order = [
                'out_trade_no' => $tradeNo,
                'total_fee' => bcmul(request('amount'), 100, 0), // 单位：分
                'body' => '丸子代练-账户余额充值',
                'trade_type' => 'NATIVE',
                'notify_url' => route('finance.balance-recharge.wechat-notify'),
            ];
            $result = Pay::wechat(config('pay.wechat'))->scan($order);

        }
        DB::commit();

        return view('front.finance.balance-recharge.pay', [
            'result' => $result,
            'amount' => request('amount'),
            'source' => request('source'),
        ]);

    }

    /**
     * 支付成功页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function paySuccess()
    {
        return view('front.finance.balance-recharge.pay-success');
    }

    /**
     * 微信异步回调
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function wechatNotify()
    {
        $wechat = Pay::wechat(config('pay.wechat'));

        try{
            $data = $wechat->verify();

            // 支付宝确认交易成功
            if ($data->result_code == 'SUCCESS') {
                // 查找充值订单为用户加款
                $order = BalanceRecharge::where('trade_no', $data->out_trade_no)
                    ->where('amount', bcdiv($data->cash_fee, 100))
                    ->where('source', 2)
                    ->where('status', 1)
                    ->first();
                // 查到充值订单
                if ($order) {
                    // 为用户加款
                    UserAssetService::init(12, $order->user_id, $order->amount, $order->trade_no)->recharge();
                    $order->status = 2;
                    $order->save();

                    // 发送通知
                    event((new NotificationEvent('recharge', [
                        'user_id' => $order->user_id,
                        'message' => '充值成功'
                    ])));
                }
            }
        } catch (Exception $e) {
            \Log::debug('Wechat notify Error', [$e->getMessage()]);
        }

        return $wechat->success();
    }

    /**
     * 支付宝异步回调
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function alipayNotify()
    {

        $alipay = Pay::alipay(config('pay.alipay'));

        try{
            $data = $alipay->verify();

            myLog('alipay', [$data]);

            // 支付宝确认交易成功
            if (in_array($data->trade_status,  ['TRADE_SUCCESS', 'TRADE_FINISHED'])) {
                // 查找充值订单为用户加款
                $order = BalanceRecharge::where('trade_no', $data->out_trade_no)
                    ->where('amount', $data->total_amount)
                    ->where('source', 1)
                    ->where('status', 1)
                    ->first();
                // 查到充值订单
                if ($order) {
                    // 为用户加款
                    UserAssetService::init(12, $order->user_id, $order->amount, $order->trade_no)->recharge();
                    $order->status = 2;
                    $order->save();

                    // 发送通知
                    event((new NotificationEvent('recharge', [
                        'user_id' => $order->user_id,
                        'message' => '充值成功'
                    ])));
                }
            }
        } catch (Exception $e) {
            \Log::debug('Alipay notify Error', [$e->getMessage()]);
        }

        return $alipay->success();
    }
}
