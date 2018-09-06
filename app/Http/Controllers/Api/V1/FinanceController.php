<?php

namespace App\Http\Controllers\Api\V1;

use DB;
use Auth;
use Hash;
use Exception;
use App\Exceptions\UserAsset\UserAssetBalanceException;
use App\Services\UserAssetService;
use App\Models\BalanceRecharge;
use App\Models\BalanceWithdraw;
use App\Models\UserAssetFlow;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yansongda\Pay\Pay;

/**
 *  资金管理
 * Class FinanceController
 * @package App\Http\Controllers\Api\V1
 */
class FinanceController extends Controller
{
    /**
     *  资金流水
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function flows(Request $request)
    {
        try {
            $user = Auth::user();

            // 查看资金流水权限
            if (! $user->could('finance.asset-flow')) {
                return response()->apiJson(1005);
            }

            $userAssetFlows = UserAssetFlow::where('user_id', $user->parent_id)
                ->select(['id', 'type', 'amount', 'balance', 'created_at'])
                ->latest('id')->paginate(request('page_size', 20));

            return response()->apiJson(0, [
                'total' => $userAssetFlows->total(),
                'total_page' => $userAssetFlows->lastPage(),
                'current_page' => $userAssetFlows->currentPage(),
                'page_size' => $userAssetFlows->perPage(),
                'list' => $userAssetFlows->items()
            ]);
        } catch (Exception $e) {
            myLog('wx-profile-flows-error', ['用户:' => $user->id ?? '', '失败:' => $e->getMessage()]);
            return response()->apiJson(1003);
        }
    }

    /**
     *  资金流水详情
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function flowsShow(Request $request)
    {
        try {
            $user = Auth::user();

            // 查看资金流水权限
            if (! $user->could('finance.asset-flow')) {
                return response()->apiJson(1005);
            }

            if (is_null(request('id'))) {
                return response()->apiJson(1001); // 参数缺失
            }

            $userAssetFlow = UserAssetFlow::find(request('id'));

            $data['type'] = config('user_asset.type')[$userAssetFlow->type];
            $data['amount'] = $userAssetFlow->amount;
            $data['created_at'] = $userAssetFlow->created_at->toDateTimeString();
            $data['remark'] = $userAssetFlow->remark;

            return response()->apiJson(0, $data);
        } catch (Exception $e) {
            myLog('wx-profile-flowsShow-error', ['用户:' => $user->id ?? '', '失败:' => $e->getMessage()]);
            return response()->apiJson(1003);
        }
    }

    /**
     *  提现
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function withdraw(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();

            // 提现权限
            if (! $user->could('finance.balance-withdraw')) {
                return response()->apiJson(1005);
            }

            if (is_null(request('amount'))) {
                return response()->apiJson(1001); // 参数缺失
            }

            if (! $user->parent->realNameCertification) {
                return response()->apiJson(3005); // 实名认证申请信息不存在
            }

            if (! Hash::check(request('pay_password'), $user->pay_password)) {
                return response()->apiJson(4002); // 支付密码错误
            }

            // 计算手续费
            $poundage = bcmul(request('amount'), 0.01);
            // 实际到账金额
            $amount = bcsub(request('amount'), $poundage);

            // 创建提现记录
            $record = BalanceWithdraw::create([
                'user_id' => $user->parent_id,
                'trade_no' => generateOrderNo(),
                'real_amount' => $amount,
                'amount' => request('amount'),
                'poundage' => $poundage,
                'real_name' => $user->parent->realNameCertification->real_name,
                'bank_card' => $user->parent->realNameCertification->bank_card,
                'bank_name' => $user->parent->realNameCertification->bank_name,
                'status' => 1
            ]);
            // 冻结资金
            UserAssetService::init(35, $user->parent_id, $amount, $record->trade_no)->frozen();
        } catch (UserAssetBalanceException $balanceException) {
            return response()->apiJson(4001);
        } catch (Exception $e) {
            myLog('wx-profile-withdraw-error', ['用户:' => $user->id ?? '', '失败:' => $e->getMessage()]);
            return response()->apiJson(1003);
        }
        DB::commit();

        return response()->apiJson(0);
    }

    /**
     *  充值
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function recharge(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();

            if (is_null(request('amount'))) {
                return response()->apiJson(1001); // 参数缺失
            }

            // 生成充值单号
            $tradeNo = generateOrderNo();

            BalanceRecharge::create([
                'user_id' => $user->parent_id,
                'amount' => request('amount'),
                'trade_no' => $tradeNo,
                'source' => 2
            ]);

            $order = [
                'out_trade_no' => $tradeNo,
                'total_fee' => bcmul(request('amount'), 100, 0), // 单位：分
                'body' => '丸子代练-账户余额充值',
                'notify_url' => route('api.finance.wechat-notify'),
                'openid' => $user->wechat_open_id,
            ];
            $result = Pay::wechat(config('pay.wechat'))->miniapp($order);

            if (! $result) {
                return response()->apiJson(1003);
            }
        } catch (Exception $e) {
            myLog('wx-profile-recharge-error', ['用户:' => $user->id ?? '', '失败:' => $e->getMessage()]);
            return response()->apiJson(1003);
        }
        DB::commit();

        return response()->apiJson(0, $result);
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
     *  提现信息
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function withdrawInfo(Request $request)
    {
        try {
            $user = Auth::user();

            $data = [
                'balance' => auth()->user()->userAsset->balance,
                'rate' => 0.01,
                'tips' => '充值说明',
                'min_amount' => 0.01,
                'max_amount' => 1000,
            ];

        } catch (Exception $e) {
            myLog('wx-profile-withdrawInfo-error', ['用户:' => $user->id ?? '', '失败:' => $e->getMessage()]);
            return response()->apiJson(1003);
        }

        return response()->apiJson(0, $data);
    }
}
