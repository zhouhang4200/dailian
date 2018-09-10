<?php

namespace App\Http\Controllers\Front;

use App\Services\SmSApiService;
use Auth;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\RealNameCertification;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * 个人资料
 * Class ProfileController
 * @package App\Http\Controllers\Front
 */
class ProfileController extends Controller
{
    /**
     * @var array
     */
    protected static $extensions = ['png', 'jpg', 'jpeg', 'gif'];

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $parentUser = User::find($user->parent_id);

        $realNameCertification = RealNameCertification::where('user_id', $user->parent_id)->first() ?? '';

        return view('front.profile.index',compact('parentUser', 'user', 'realNameCertification'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request)
    {
        $user = User::find(Auth::user()->parent_id);

        return view('front.profile.edit', compact('user'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        if (Auth::user()->isParent()) {
            $user = Auth::user();
        } else {
            $user = Auth::user()->parent;
        }

        if ($request->data['qq'] && ! is_numeric($request->data['qq'])) {
            return response()->json(['status' => 0, 'message' => 'QQ号必须为数字!']);
        }
        $user->name = $request->data['name'];
        $user->age = $request->data['age'];
        $user->qq = $request->data['qq'];
        $user->wechat = $request->data['wechat'];
        $bool = $user->save();

        if ($bool) {
            return response()->json(['status' => 1, 'message' => '修改成功!']);
        }
        return response()->json(['status' => 0, 'message' => '修改失败!']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function avatarShow(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = public_path("/resources/users/".date('Ymd')."/");
            $imagePath = $this->uploadImage($file, $path);
            return response()->json(['status' => 1, 'message' => $imagePath]);
        }
    }

    /**
     * 图片上传
     * @param UploadedFile $file
     * @param $path
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function uploadImage(UploadedFile $file, $path)
    {
        $extension = $file->getClientOriginalExtension();

        if ($extension && ! in_array(strtolower($extension), static::$extensions)) {
            return response()->json(['status' => 0, 'message' => $path]);
        }

        if (! $file->isValid()) {
            return response()->json(['status' => 0, 'message' => $path]);
        }

        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
        $randNum = rand(1, 100000000) . rand(1, 100000000);
        $fileName = time().substr($randNum, 0, 6).'.'.$extension;
        $path = $file->move($path, $fileName);
        $path = strstr($path, '/resources');

        return str_replace('\\', '/', $path);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function avatarUpdate(Request $request)
    {
        $user = Auth::user();
        $user->avatar = $request->data['avatar'];

        if (empty($user->avatar)) {
            return response()->json(['status' => 0, 'message' => '请先上传头像!']);
        }

        $bool = $user->save();

        if ($bool) {
            return response()->json(['status' => 1, 'message' => '修改成功!', 'path' => $user->avatar]);
        }
        return response()->json(['status' => 0, 'message' => '修改失败!']);
    }

    /**
     * 修改密码
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword()
    {
        $oldPassword = clientRSADecrypt(request('old_password'));
        $newPassword = clientRSADecrypt(request('password'));

        $currentUser = request()->user();
        if (! \Hash::check($oldPassword, $currentUser->password)) {
            return response()->json(['status' => 0, 'message' => '原密码错误']);
        }
        $currentUser->password = bcrypt($newPassword);
        $currentUser->save();

        return response()->json(['status' => 1, 'message' => '修改成功']);
    }

    /**
     * 设置密码
     * @return \Illuminate\Http\JsonResponse
     */
    public function setPayPassword()
    {
        $newPassword = clientRSADecrypt(request('password'));

        $currentUser = request()->user();
        $currentUser->pay_password = bcrypt($newPassword);
        $currentUser->save();

        return response()->json(['status' => 1, 'message' => '设置成功']);
    }

    /**
     * 修改支付密码
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePayPassword()
    {
        $oldPayPassword = clientRSADecrypt(request('old_password'));
        $newPayPassword = clientRSADecrypt(request('password'));

        $currentUser = request()->user();
        if (! \Hash::check($oldPayPassword, $currentUser->pay_password)) {
            return response()->json(['status' => 0, 'message' => '原支付密码错误']);
        }
        $currentUser->pay_password = bcrypt($newPayPassword);
        $currentUser->save();

        return response()->json(['status' => 1, 'message' => '修改成功']);
    }

    /**
     * 重置支付密码
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function resetPayPassword()
    {
        $newPayPassword = clientRSADecrypt(request('password'));

        $currentUser = request()->user();
        if (request('verification_code') != cache()->get(config('redis_key.profile.pay_password_verification_code') . auth()->id())) {
            return response()->json(['status' => 0, 'message' => '验证码错误']);
        }
        $currentUser->pay_password = bcrypt($newPayPassword);
        $currentUser->save();

        return response()->json(['status' => 1, 'message' => '修改成功']);
    }

    /**
     * 发送验证码
     * @throws \Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function resetPayPasswordVerificationCode()
    {
        $code = randomNumber();

        try {
            cache()->put(config('redis_key.profile.pay_password_verification_code') . auth()->id(), $code, 1);
            SmSApiService::send(request()->user()->phone, $code);
        } catch (\Exception $exception) {

        }
        return response()->ajaxSuccess('验证码已经发送至您手机，请注意查收-' .$code);
    }
}
