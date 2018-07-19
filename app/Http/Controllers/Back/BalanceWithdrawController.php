<?php

namespace App\Http\Controllers\Back;

use Maatwebsite\Excel\Facades\Excel;
use App\Models\BalanceWithdraw;
use App\Models\UserAssetFlow;
use Illuminate\Http\Request;
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
            $exportDatas = BalanceWithdraw::filter($filters)->with('userAssetFlow')->get();

            $this->export($exportDatas);
        }

        return view('back.finance.balance-withdraw.index', compact('startDate', 'endDate', 'type', 'status', 'tradeNo', 'userId', 'remark', 'balanceWithdraws'));
    }

    public function agree(Request $request)
    {
        try {
            $balanceWithdraw = BalanceWithdraw::find($request->id);

            $balanceWithdraw->status = 2;
            $balanceWithdraw->save();

            return response()->json(['status' => 1, 'message' => '成功']);
        }
        catch (Exception $e) {
            return response()->json(['status' => 0, 'message' => '失败']);
        }
    }

    public function refuse(Request $request)
    {
        try {
            $balanceWithdraw = BalanceWithdraw::find($request->id);

            $balanceWithdraw->remark = $request->remark;
            $balanceWithdraw->status = 3;
            $balanceWithdraw->save();

            return response()->json(['status' => 1, 'message' => '成功']);
        } catch (Exception $e) {
            return response()->json(['status' => 0, 'message' => '失败']);
        }
    }

    /**
     * @param $exportDatas
     */
    public function export($exportDatas)
    {
        try {
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
            myLog('balance-withdraw-export-error', ['message' => $e->getMessage()]);
        }
    }
}
