<?php

namespace App\Http\Controllers\Back;

use Exception;
use App\Models\RealNameCertification;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
     * 实名认证信息
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function certification(Request $request, $id)
    {
        $user = User::find($id);

        $realNameCertification = RealNameCertification::where('user_id', $id)->first();

        return view('back.user.certification', compact('user', 'realNameCertification'));
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

            return response()->ajaxSuccess('审核完成，状态：拒绝！');
        } catch (Exception $e) {
            return response()->ajaxFail('拒绝失败，请点击重试');
        }
    }
}
