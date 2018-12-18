<?php

namespace App\Http\Controllers\Back;

use App\Models\UserPoundage;
use App\Models\UserSpread;
use Redis;
use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\RealNameCertification;

/**
 * 商户管理
 * Class UserController
 * @package App\Http\Controllers\Back
 */
class UserController extends Controller
{
    /**
     * 商户列表
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $id = $request->id;
        $name = $request->name;
        $phone = $request->phone;
        $filters = compact('id', 'name', 'phone');

        $users = User::filter($filters)->where('parent_id', 0)->paginate(20);

        if ($request->ajax()) {
            return response()->json(view()->make('back.user.list', compact('users', 'id', 'name', 'phone'))->render());
        }

        return view('back.user.index', compact('users', 'id', 'name', 'phone'));
    }

    /**
     * 商户资料
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request)
    {
        $user = User::find($request->id);

        return view('back.user.show', compact('user'));
    }

    /**
     * 实名认证列表
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function certification(Request $request)
    {
        $name = $request->name;
        $status = $request->status;
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $filters = compact('name', 'status', 'startDate', 'endDate');

        $certifications = RealNameCertification::filter($filters)->oldest('status')->oldest('id')->paginate(10);

        if ($request->ajax()) {
            return response()->json(view()->make('back.user.certification-list', compact('certifications', 'status', 'name', 'startDate', 'endDate'))->render());
        }

        return view('back.user.certification', compact('certifications', 'status', 'name', 'startDate', 'endDate'));
    }

    /**
     * 实名认证信息详情
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function certificationShow(Request $request, $id)
    {
        $realNameCertification = RealNameCertification::find($id);
        $user = User::find($realNameCertification->user_id);

        return view('back.user.certification-show', compact('user', 'realNameCertification'));
    }

    /**
     * 实名认证通过
     *
     * @param Request $request
     * @return mixed
     */
    public function certificationPass(Request $request)
    {
        try {
            $realNameCertification = RealNameCertification::where('user_id', $request->userId)->first();

            $realNameCertification->status = 2;
            $realNameCertification->save();
            // 推送
            Redis::publish('certification', json_encode([
                'event' => $realNameCertification->user_id,
                'data' => '通过'
            ]));

            return response()->ajaxSuccess('审核完成，状态：通过！');
        } catch (Exception $e) {
            return response()->ajaxFail('通过失败，请点击重试');
        }
    }

    /**
     * 实名认证拒绝
     *
     * @param Request $request
     * @return mixed
     */
    public function certificationRefuse(Request $request)
    {
        try {
            $realNameCertification = RealNameCertification::where('user_id', $request->userId)->first();

            $realNameCertification->status = 3;
            $realNameCertification->remark = $request->remark;
            $realNameCertification->save();
            // 推送
            Redis::publish('certification', json_encode([
                'event' => $realNameCertification->user_id,
                'data' => '拒绝'
            ]));

            return response()->ajaxSuccess('审核完成，状态：拒绝！');
        } catch (Exception $e) {
            return response()->ajaxFail('拒绝失败，请点击重试');
        }
    }

    /**
     * 主账号封号
     *
     * @param Request $request
     * @return mixed
     */
    public function closeAccount(Request $request)
    {
        try {
            $user = User::find($request->id);
            $user->status = 2; // 禁用
            $user->save();
            // 子账号封号
            foreach ($user->children as $child) {
                $child->status = 2;
                $child->save();
            }
            return response()->ajaxSuccess('封号成功');
        } catch (Exception $e) {
            return response()->ajaxFail('封号失败，请点击重试');
        }
    }

    /**
     * 解封封号,子账号需主账号手动解封
     *
     * @param Request $request
     * @return mixed
     */
    public function openAccount(Request $request)
    {
        try {
            $user = User::find($request->id);
            $user->status = 1; // 启用
            $user->save();

            return response()->ajaxSuccess('解封成功');
        } catch (Exception $e) {
            return response()->ajaxFail('解封失败，请点击重试');
        }
    }

    /**
     * 用户手续费设置
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function poundage()
    {
        try {
            $user = User::find(request('id'));

            $userPoundage = UserPoundage::where('user_id', request('id'))->first() ?? '';

            return view('back.user.poundage', compact('user', 'userPoundage'));
        } catch (Exception $e) {

        }
    }

    /**
     * 手续费新增
     *
     * @return mixed
     */
    public function poundageStore()
    {
        try {
            $user = User::find(request('id'));

            if (!request('send_poundage') && !request('take_poundage')) {
                return response()->ajaxFail('请填写相应的手续费比例！');
            }

            if (request('send_poundage') > 1 || request('take_poundage') > 1) {
                return response()->ajaxFail('手续费比例不得大于1！');
            }

            UserPoundage::updateOrCreate([
                'user_id' => $user->id
            ], [
                'user_id' => $user->id,
                'send_poundage' => request('send_poundage', 0),
                'take_poundage' => request('take_poundage', 0),
            ]);

            return response()->ajaxSuccess('操作成功！');
        } catch (Exception $e) {
            return response()->ajaxFail('操作失败！');
        }
    }

    /**
     * 手续费修改
     *
     * @return mixed
     */
    public function poundageUpdate()
    {
        try {
            $userPoundage = UserPoundage::where('user_id', request('id'))->first();

            if (!request('send_poundage') && !request('take_poundage')) {
                $userPoundage->delete();
                return response()->ajaxSuccess('操作成功！');
            }

            if (request('send_poundage') > 1 || request('take_poundage') > 1) {
                return response()->ajaxFail('手续费比例不得大于1！');
            }

            $userPoundage->send_poundage = request('send_poundage', 0);
            $userPoundage->take_poundage = request('take_poundage', 0);
            $userPoundage->save();

            return response()->ajaxSuccess('操作成功！');
        } catch (Exception $e) {
            return response()->ajaxFail('操作失败！');
        }
    }

    /**
     * 用户推广返利设置
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function spread()
    {
        try {
            $user = User::find(request('id'));

            $userSpread = UserSpread::where('user_id', request('id'))->first() ?? '';

            return view('back.user.spread', compact('user', 'userSpread'));
        } catch (Exception $e) {

        }
    }

    /**
     * 推广返利增加
     *
     * @return mixed
     */
    public function spreadStore()
    {
        try {
            $user = User::find(request('id'));

            if (!request('spread_rate')) {
                return response()->ajaxFail('请填写相应的推广返利比例比例！');
            }

            if (request('spread_rate') > 1) {
                return response()->ajaxFail('推广返利比例不得大于1！');
            }

            UserSpread::updateOrCreate([
                'user_id' => $user->id
            ], [
                'user_id' => $user->id,
                'spread_rate' => request('spread_rate', 0)
            ]);

            return response()->ajaxSuccess('操作成功！');
        } catch (Exception $e) {
            return response()->ajaxFail('操作失败！');
        }
    }

    /**
     * 推广返利修改
     *
     * @return mixed
     */
    public function spreadUpdate()
    {
        try {
            $userSpread = UserSpread::where('user_id', request('id'))->first();

            if (request('spread_rate') > 1) {
                return response()->ajaxFail('推广返利比例不得大于1！');
            }

            if (!request('spread_rate')) {
                $userSpread->delete();
                return response()->ajaxSuccess('操作成功！');
            }

            $userSpread->spread_rate = request('spread_rate');
            $userSpread->save();

            return response()->ajaxSuccess('操作成功！');
        } catch (Exception $e) {
            return response()->ajaxFail('操作失败！');
        }
    }

    public function poundageSetting()
    {
        try {
            $user = User::find(request('id'));

            if (request('send_poundage') && request('send_poundage') > 1) {
                return response()->ajaxFail('发单手续费比例不得大于1！');
            }

            if (request('take_poundage') && request('take_poundage') > 1) {
                return response()->ajaxFail('接单手续费比例不得大于1！');
            }

            if (request('spread_rate') && is_null(request('take_poundage'))) {
                return response()->ajaxFail('请先设置接单手续费比例！');
            }

            if (request('spread_rate') && request('spread_rate') > 1) {
                return response()->ajaxFail('推广返利比例不得大于1！');
            }

            if (request('spread_rate') && request('spread_rate') > request('take_poundage')) {
                return response()->ajaxFail('推广返利比例不得大于接单手续费比例！');
            }

            if (request('send_poundage') > 0 && request('send_poundage') < 0.01) {
                return response()->ajaxFail('请填写2位小数！');
            }

            if (request('take_poundage') > 0 && request('take_poundage') < 0.01) {
                return response()->ajaxFail('请填写2位小数！');
            }

            if (request('spread_rate') > 0 && request('spread_rate') < 0.01) {
                return response()->ajaxFail('请填写2位小数！');
            }

            if (request('send_poundage') || request('take_poundage')) {
                UserPoundage::updateOrCreate(['user_id' => $user->id], [
                    'user_id' => $user->id,
                    'send_poundage' => request('send_poundage', 0),
                    'take_poundage' => request('take_poundage', 0),
                ]);
            }

            if (!request('send_poundage') && !request('take_poundage')) {
                UserPoundage::where('user_id', $user->id)->delete();
            }

            if (request('spread_rate')) {
                UserSpread::updateOrCreate(['user_id' => $user->id], [
                    'user_id' => $user->id,
                    'spread_rate' => request('spread_rate'),
                ]);
            }

            if (!request('spread_rate')) {
                UserSpread::where('user_id', $user->id)->delete();
            }

            return response()->ajaxSuccess('操作成功！');
        } catch (Exception $e) {
            return response()->ajaxFail('操作失败！');
        }
    }
}
