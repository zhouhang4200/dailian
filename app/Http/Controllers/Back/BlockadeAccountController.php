<?php

namespace App\Http\Controllers\Back;

use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\BlockadeAccount;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;

/**
 * 后台封号
 * Class BlockadeAccountController
 * @package App\Http\Controllers\Back
 */
class BlockadeAccountController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        return view('back.blockade-account.index');
        $nameOrId = $request->nameOrId;
        $startTime = $request->startTime;
        $endTime = $request->endTime;
        $filters = compact('nameOrId', 'startTime', 'endTime');

        $blockadeAccounts = BlockadeAccount::filter($filters)->paginate(10);

        return view('back.blockade-account.index', compact('nameOrId', 'startTime', 'endTime', 'blockadeAccounts'));
    }

    /**
     * table表数据
     * @param Request $request
     * @return array
     */
    public function table(Request $request)
    {
        $nameOrId = $request->nameOrId;
        $startTime = $request->startTime;
        $endTime = $request->endTime;
        $type = $request->type;

        if ($type == 0) {
            $filters = compact('nameOrId', 'startTime', 'endTime');
        } else {
            $filters = compact('nameOrId', 'startTime', 'endTime', 'type');
        }

        $blockadeAccounts = BlockadeAccount::filter($filters)->paginate(10);

        $arr = [];
        foreach ($blockadeAccounts as $k => $blockadeAccount) {
            $arr[$k]['id'] = $blockadeAccount->id;
            $arr[$k]['nameOrId'] = $blockadeAccount->user->name.'/'.$blockadeAccount->user->id;
            $arr[$k]['reason'] = $blockadeAccount->reason;
            $arr[$k]['remark'] = $blockadeAccount->remark;
            $arr[$k]['type'] = $blockadeAccount->type;
            $arr[$k]['start_time'] = $blockadeAccount->start_time;
            $arr[$k]['end_time'] = $blockadeAccount->end_time ?? '--';
            if ($blockadeAccount->type == 1) {
                $time = bcsub(Carbon::parse($blockadeAccount->end_time)->timestamp, Carbon::now()->timestamp, 0);
                $arr[$k]['left_time'] = sec2Time($time);
            } elseif ($blockadeAccount->type == 2) {
                $arr[$k]['left_time'] = '永久';
            } elseif ($blockadeAccount->type == 3) {
                $arr[$k]['left_time'] = '封号结束';
            } else {
                $arr[$k]['left_time'] = '未知错误';
            }
        }

        return [
            'code' => 0,
            'msg' => '',
            'count' => $blockadeAccounts->total(),
            'data' =>  $arr,
        ];
    }

    /**
     * 修改数据
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request)
    {
        $data = $request->data;

        try {
            $blockadeAccount = BlockadeAccount::find($data['id']);
            $blockadeAccount->remark = htmlentities($data['remark']);
            $blockadeAccount->save();

            return response()->ajaxSuccess('修改成功');
        } catch (Exception $e) {
            return response()->ajaxFail('修改失败');
        }
    }

    /**
     * 封号添加页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        return view('back.blockade-account.create');
    }

    /**
     * 封号添加逻辑
     * @param Request $request
     */
    public function store(Request $request)
    {
        try {
            $data = $request->data;

            if (isset($data['type']) && $data['type'] == 2) {
                $data['type'] = 2;
            } else {
                $data['type'] = 1;
            }

            $blockadeAccount = BlockadeAccount::create($data);
            // 推送到广播
            if ($blockadeAccount->type == 2) {
                $message = '您的账号已被封号，封号原因：'.$blockadeAccount->reason.'，封号时间：永久，如有异议请联系客服。';
            } elseif ($blockadeAccount->type == 1) {
                $time = bcsub(Carbon::parse($blockadeAccount->end_time)->timestamp, Carbon::now()->timestamp, 0);
                $leftTime= sec2Time($time);
                $message = '您的账号已被封号，封号原因：'.$blockadeAccount->reason.'，封号时间：'.$blockadeAccount->start_time.'至'.$blockadeAccount->end_time.' ，剩余时长：'.$leftTime.'，如有异议请联系客服。';
            }

            Redis::publish('blockade', json_encode([
                'event' => $blockadeAccount->user_id,
                'data' => $message
            ]));

            return response()->ajaxSuccess('添加成功');
        } catch (Exception $e) {
            return response()->ajaxFail('添加失败' . $e->getMessage());
        }
    }

    /**
     * 计算各状态的数量
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function count(Request $request)
    {
        $nameOrId = $request->nameOrId;
        $startTime = $request->startTime;
        $endTime = $request->endTime;
        $type = $request->type;

        $filters = compact('nameOrId', 'startTime', 'endTime');

        $arr = [];
        $arr['all'] = BlockadeAccount::filter($filters)->count() ?? 0;
        $arr['blockading'] = BlockadeAccount::filter($filters)->where('type', 1)->count() ?? 0;
        $arr['blockaded'] = BlockadeAccount::filter($filters)->where('type', 2)->count() ?? 0;
        $arr['unblockade'] = BlockadeAccount::filter($filters)->where('type', 3)->count() ?? 0;

        return response()->json(['status' => 1, 'data' => $arr]);
    }

    /**
     * 解除封号
     * @param Request $request
     * @return mixed
     */
    public function unblockade(Request $request)
    {
        try {
            $blockadeAccount = BlockadeAccount::find($request->id);
            $blockadeAccount->type = 3;
            $blockadeAccount->save();

            return response()->ajaxSuccess('解除封号成功');
        } catch (Exception $e) {
            return response()->ajaxFail('解除封号失败');
        }
    }

    /**
     * 调整时间
     * @param Request $request
     * @return mixed
     */
    public function time(Request $request)
    {
        try {
            $data = $request->data;

            if (! isset($data['type']) && ! isset($data['end_time'])) {
                return response()->ajaxFail('调整时间失败,请选择结束时间或勾选永久');
            }
            $blockadeAccount = BlockadeAccount::find($request->id);
            // 如果是封号结束的，则抛出错误
            if ($blockadeAccount->type == 3) {
                return response()->ajaxFail('封号已结束，如需封号请重新添加封号记录');
            }
            $blockadeAccount->start_time = $data['start_time'];

            if (isset($data['end_time']) && ! empty($data['end_time']) && ! isset($request->data['type'])) {
                $blockadeAccount->type = 1;
                $blockadeAccount->end_time = $data['end_time'];
            }

            if (isset($data['type']) && ! empty($data['type'])) {
                $blockadeAccount->end_time = null;
                $blockadeAccount->type = $data['type'];
            }
            $blockadeAccount->save();
            return response()->ajaxSuccess('调整时间成功');
        } catch (Exception $e) {
            return response()->ajaxFail('调整时间失败');
        }
    }
}
