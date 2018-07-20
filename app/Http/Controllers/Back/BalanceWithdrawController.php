<?php

namespace App\Http\Controllers\Back;

use DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\BalanceWithdraw;
use App\Models\UserAssetFlow;
use Illuminate\Http\Request;
use App\Services\UserAssetServices;
use App\Http\Controllers\Controller;

class BalanceWithdrawController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $type = $request->type;
        $status = $request->status;
        $tradeNo = $request->tradeNo;
        $userId = $request->userId;
        $remark = $request->remark;

        $filters = compact('startDate', 'endDate', 'type', 'status', 'tradeNo', 'userId', 'remark');

        $balanceWithdraws = BalanceWithdraw::filter($filters)->with('userAssetFlow')->paginate(20);

        if ($request->export == 1) {
            $this->export($filters);
        }

        return view('back.finance.balance_withdraw.index', compact('startDate', 'endDate', 'type', 'status', 'tradeNo', 'userId', 'remark', 'balanceWithdraws'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function agree(Request $request)
    {
        DB::beginTransaction();
        try {
            $balanceWithdraw = BalanceWithdraw::find($request->id);

            $balanceWithdraw->status = 2;
            $balanceWithdraw->save();

            $userAssetFlow = UserAssetFlow::where('trade_no', $balanceWithdraw->trade_no)->first();
//            dd($userAssetFlow->sub_type, $userAssetFlow->user_id, $userAssetFlow->amount, $userAssetFlow->trade_no);
            UserAssetServices::init($userAssetFlow->sub_type, $userAssetFlow->user_id, $userAssetFlow->amount, $userAssetFlow->trade_no)->expendFromFreeze();
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

            UserAssetServices::init($userAssetFlow->sub_type, $userAssetFlow->user_id, $userAssetFlow->amount, $userAssetFlow->trade_no)->unFreeze();
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['status' => 0, 'message' => '失败']);
        }
        DB::commit();
        return response()->json(['status' => 1, 'message' => '成功']);
    }

    /**
     * @param $exportDatas
     */
    public function export($filters = [])
    {
        try {
            $exportDatas = BalanceWithdraw::filter($filters)->with('userAssetFlow')->get();
            // 标题
            $title = [
                '提现单号', '主账号', '当前余额', '当前冻结', '姓名', '开户行', '卡号', '提现金额', '类型', '状态', '管理员备注', '创建时间', '更新时间',
            ];
            // 数组分割,反转
            $chunkExportDatas = array_chunk(array_reverse($exportDatas->toArray()), 1000);

            Excel::create('提现记录', function ($excel) use ($chunkExportDatas, $title) {
                foreach ($chunkExportDatas as $no => $chunkExportData) {
                    // 内容
                    $datas = [];
                    foreach ($chunkExportData as $key => $exportData) {
                        $datas[] = [
                            $exportData['trade_no'],
                            $exportData['user_id'],
                            $exportData['user_asset_flow']['balance'],
                            $exportData['user_asset_flow']['frozen'],
                            $exportData['real_name'],
                            $exportData['bank_name'],
                            $exportData['bank_card'],
                            $exportData['amount']+0,
                            config('user_asset_type.type')[$exportData['user_asset_flow']['type']],
                            config('balance_withdraw.status')[$exportData['status']],
                            $exportData['user_asset_flow']['remark'] ?? '--',
                            $exportData['created_at'],
                            $exportData['updated_at'],
                        ];
                    }
                    // 将标题加入到数组
                    array_unshift($datas, $title);
                    // 每页多少数据
                    $excel->sheet('第'.++$no.'页', function ($sheet) use ($datas) {
                        $sheet->rows($datas);
                    });
                }
            })->export('xls');
        } catch (Exception $e) {
            myLog('balance_withdraw_export_error', ['message' => $e->getMessage()]);
        }
    }
}
