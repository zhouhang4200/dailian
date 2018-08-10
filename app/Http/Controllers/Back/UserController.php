<?php

namespace App\Http\Controllers\Back;

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
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request, $id)
    {
        $user = User::find($id);

        return view('back.user.show', compact('user'));
    }

    /**
     * 实名认证列表
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
}
