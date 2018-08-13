<?php

namespace App\Http\Controllers\Back;

use App\Exceptions\UserAsset\UserAssetBalanceException;
use App\Models\FineTicket;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserAsset;
use App\Services\UserAssetService;

/**
 * Class FineTicketController
 * @package App\Http\Controllers\Back
 */
class FineTicketController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('back.fine-ticket.index', [
            'fineTickets' => FineTicket::condition(request()->all())->paginate(),
            'statusCount' => FineTicket::selectRaw('status, count(1) as count')
                ->groupBy('status')->pluck('count', 'status')->toArray(),
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('back.fine-ticket.create', [
            'users' => User::query()->where('parent_id', 0)->get()
        ]);
    }


    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        try {
            $tradeNo = generateOrderNo();
            UserAssetService::init(36, request('user_id'), request('amount'), $tradeNo)->frozen();

            FineTicket::create([
                'user_id' => request('user_id'),
                'trade_no' => $tradeNo,
                'relation_trade_no' => request('relation_trade_no'),
                'amount' => request('amount'),
                'reason' => request('reason'),
                'remark' => request('remark'),
            ]);

            return redirect(route('admin.user.fine-ticket.create'))->with('success', '添加成功');
        } catch (UserAssetBalanceException $exception) {
            return redirect(route('admin.user.fine-ticket.create'))->with('fail', '账号余额不足');
        } catch (\Exception $exception) {
            return redirect(route('admin.user.fine-ticket.create'))->with('fail', $exception->getMessage());
        }
    }

    /**
     * 解冻
     * @return mixed
     */
    public function unfrozen()
    {
        try {
            $fineTicket = FineTicket::where('id', request('id'))->first();

            UserAssetService::init(46, $fineTicket->user_id, $fineTicket->amount, $fineTicket->trade_no)->unfrozen();
            $fineTicket->status = 2;
            $fineTicket->save();
        } catch (\Exception $exception) {
            return response()->ajaxFail($exception->getMessage());
        }
        return response()->ajaxSuccess();
    }

    /**
     * 罚款
     * @return mixed
     */
    public function fine()
    {
        try {
            $fineTicket = FineTicket::where('id', request('id'))->first();

            UserAssetService::init(65, $fineTicket->user_id, $fineTicket->amount, $fineTicket->trade_no)->expendFromFrozen();
            $fineTicket->status = 3;
            $fineTicket->save();
        } catch (\Exception $exception) {
            return response()->ajaxFail($exception->getMessage());
        }
        return response()->ajaxSuccess();
    }
}
