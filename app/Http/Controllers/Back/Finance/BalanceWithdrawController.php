<?php

namespace App\Http\Controllers\Back\Finance;

use DB;
use Exception;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\BalanceWithdraw;
use App\Models\UserAssetFlow;
use Illuminate\Http\Request;
use App\Services\UserAssetService;
use App\Http\Controllers\Controller;

class BalanceWithdrawController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $status = $request->status;
        $tradeNo = $request->tradeNo;
        $userId = $request->userId;
        $remark = $request->remark;

        $filters = compact('startDate', 'endDate', 'status', 'tradeNo', 'userId', 'remark');
        $balanceWithdraws = BalanceWithdraw::filter($filters)->with(['userAssetFlows' =>  function ($query) {
            $query->latest('id')->first();
        }])->paginate(20);

        return view('back.finance.balance-withdraw.index', compact('startDate', 'endDate', 'type', 'status', 'tradeNo', 'userId', 'remark', 'balanceWithdraws'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws Exception
     */
    public function agree(Request $request)
    {
        DB::beginTransaction();
        try {
            $balanceWithdraw = BalanceWithdraw::find($request->id);

            $balanceWithdraw->status = 2;
            $balanceWithdraw->save();

            $userAssetFlow = UserAssetFlow::where('trade_no', $balanceWithdraw->trade_no)->first();

            UserAssetService::init(35, $userAssetFlow->user_id, $userAssetFlow->amount, $userAssetFlow->trade_no)->expendFromFrozen();
        }
        catch (Exception $e) {
            DB::rollback();
            return response()->json(['status' => 0, 'message' => '失败']);
        }
        DB::commit();
        return response()->json(['status' => 1, 'message' => '成功']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws Exception
     */
    public function refuse(Request $request)
    {
        DB::beginTransaction();
        try {
            $balanceWithdraw = BalanceWithdraw::find($request->id);

            $balanceWithdraw->remark = $request->remark;
            $balanceWithdraw->status = 3;
            $balanceWithdraw->save();

            $userAssetFlow = UserAssetFlow::where('trade_no', $balanceWithdraw->trade_no)->first();

            UserAssetService::init(45, $userAssetFlow->user_id, $userAssetFlow->amount, $userAssetFlow->trade_no)->unfrozen();
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['status' => 0, 'message' => '失败']);
        }
        DB::commit();
        return response()->json(['status' => 1, 'message' => '成功']);
    }

    /**
     * 提现导出
     */
    public function export(Request $request)
    {
        try {
            $startDate = $request->startDate;
            $endDate = $request->endDate;
            $status = $request->status;
            $tradeNo = $request->tradeNo;
            $userId = $request->userId;
            $remark = $request->remark;

            $filters = compact('startDate', 'endDate', 'status', 'tradeNo', 'userId', 'remark');

            $title = [
                '提现单号', '主账号', '当前余额', '当前冻结', '姓名', '开户行', '卡号', '提现金额', '类型', '管理员备注', '创建时间', '更新时间',
            ];

            $query = BalanceWithdraw::filter($filters)->with(['userAssetFlows' =>  function ($query) {
                $query->latest('id')->first();
            }]);

            return export($title, '提现记录', $query, function ($query, $out){
                $query->chunk(100, function ($chunkExportDatas) use ($out) {
                    foreach ($chunkExportDatas as $exportData) {
                        $data = [
                            $exportData->trade_no,
                            $exportData->user_id,
                            $exportData->userAssetFlows[0]->balance,
                            $exportData->userAssetFlows[0]->frozen,
                            $exportData->real_name,
                            $exportData->bank_name,
                            $exportData->bank_card,
                            $exportData->amount+0,
                            config('balance_withdraw.status')[$exportData->status],
                            $exportData->remark ?? '--',
                            $exportData->created_at,
                            $exportData->updated_at,
                        ];
                        fputcsv($out, $data);
                    }
                });
            });
        } catch (Exception $e) {
            myLog('balance_withdraw_export_error', ['message' => $e->getMessage()]);
        }
    }
}
