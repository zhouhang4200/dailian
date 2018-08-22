<?php

namespace App\Http\Controllers\Api\V1;

use Auth;
use Hash;
use Exception;
use Illuminate\Http\UploadedFile;
use App\Models\RealNameCertification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    // 允许上传图片类型
    private static $extensions = ['png', 'jpg', 'jpeg', 'gif'];

    /**
     *  小程序首页个人资料
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();

            $data['id'] = $user->id;
            $data['name'] = $user->name;
            $data['age'] = $user->age;
            $data['enail'] = $user->enail;
            $data['phone'] = $user->phone;
            $data['wechat'] = $user->wechat;
            $data['qq'] = $user->qq;
            $data['avatar'] = $user->avatar;
            $data['status'] = $user->status;
            $data['certification_status'] = $user->realNameCertification ? $user->realNameCertification->status : 0;
            $data['balance'] = $user->userAsset ? $user->userAsset->balance : 0;
            $data['frozen'] = $user->userAsset ? $user->userAsset->frozen : 0;

            return response()->apiJson(0, $data);
        } catch (Exception $e) {
            myLog('wx-profile-error', ['用户:' => $user->id ?? '', '失败:' => $e->getMessage()]);
            return response()->apiJson(1003);
        }
    }

    /**
     *  修改资料
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function update(Request $request)
    {
        try {
            $user = Auth::user();
            $file = $request->avatar;
            $path = public_path("/resources/users/".$user->id.'/'.date('Ymd')."/");
            $user->avatar = static::uploadImage($file, $path);
            $user->name = request('name');
            $user->qq = request('qq');
            $user->signature = request('signature');
            $user->email = request('email');
            $user->save();

            return response()->apiJson(0);
        } catch (Exception $e) {
            myLog('wx-profile-update-error', ['用户:' => $user->id ?? '', '失败:' => $e->getMessage()]);
            return response()->apiJson(1003);
        }
    }

    /**
     * 接收远程传过来的图片
     * @param UploadedFile $file
     * @param $path
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public static function uploadImage(UploadedFile $file, $path)
    {
        // 获取可传输的图片类型
        $extension = $file->getClientOriginalExtension();

        if ($extension && ! in_array(strtolower($extension), static::$extensions)) {
            return response()->apiJson(3001);
        }
        // 判断上传是否为空
        if (! $file->isValid()) {
            return response()->apiJson(3002);
        }
        // 不存在存储路径的时候指定路径
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
        // 图片随机命名
        $randNum = rand(1, 100000000) . rand(1, 100000000);
        $fileName = time().substr($randNum, 0, 6).'.'.$extension;
        // 保存图片
        $path = $file->move($path, $fileName);
        $path = strstr($path, '/resources');
        // 返回图片路径
        return str_replace('\\', '/', $path);
    }

    /**
     *  设置支付密码
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function payPasswordSet(Request $request)
    {
        try {
            if (is_null(request('pay_password'))) {
                return response()->apiJson(1001); // 参数缺失
            }
            $user = Auth::user();
            $user->pay_password = bcrypt(request('pay_password'));
            $user->save();

            return response()->apiJson(0);
        } catch (Exception $e) {
            myLog('wx-profile-payPasswordSet-error', ['用户:' => $user->id ?? '', '失败:' => $e->getMessage()]);
            return response()->apiJson(1003);
        }
    }

    /**
     *  修改支付密码
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function payPasswordReset(Request $request)
    {
        try {
            if (is_null(request('old_pay_password')) || is_null(request('new_pay_password'))) {
                return response()->apiJson(1001); // 参数缺失
            }
            $user = Auth::user();

            if (! Hash::check(request('old_pay_password'), $user->pay_password)) {
                return response()->apiJson(3003); // 原密码错误
            }
            $user->pay_password = bcrypt(request('pay_password'));
            $user->save();

            return response()->apiJson(0);
        } catch (Exception $e) {
            myLog('wx-profile-payPasswordSet-error', ['用户:' => $user->id ?? '', '失败:' => $e->getMessage()]);
            return response()->apiJson(1003);
        }
    }

    /**
     *  找回支付密码
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function payPasswordRefound(Request $request)
    {
        try {
            if (is_null(request('phone')) || is_null(request('new_pay_password'))) {
                return response()->apiJson(1001); // 参数缺失
            }
            $user = Auth::user();

            if ($user->phone != request('phone')) {
                return response()->apiJson(2004); // 请填写注册时候手机号
            }
            $user->pay_password = bcrypt(request('new_pay_password'));
            $user->save();

            return response()->apiJson(0);
        } catch (Exception $e) {
            myLog('wx-profile-payPasswordSet-error', ['用户:' => $user->id ?? '', '失败:' => $e->getMessage()]);
            return response()->apiJson(1003);
        }
    }

    /**
     *  填写实名认证
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function certification(Request $request)
    {
        try {
            if (
                is_null(request('real_name')) ||
                is_null(request('identity_card')) ||
                is_null(request('bank_card')) ||
                is_null(request('bank_name')) ||
                is_null(request('identity_card_front')) ||
                is_null(request('identity_card_back')) ||
                is_null(request('identity_card_hand'))
            ) {
                return response()->apiJson(1001); // 参数缺失
            }
            $user = Auth::user();

            if (! $user->isParent()) {
                return response()->apiJson(3004); // 只有主账号才能填写实名认证
            }

            $file1 = request('identity_card_front');
            $file2 = request('identity_card_back');
            $file3 = request('identity_card_hand');
            $path = public_path("/resources/certification/".$user->id.'/'.date('Ymd')."/");

            $datas['user_id'] = $user->id;
            $datas['real_name'] = request('real_name');
            $datas['identity_card'] = request('identity_card');
            $datas['bank_card'] = request('bank_card');
            $datas['bank_name'] = request('bank_name');
            $datas['identity_card_front'] = static::uploadImage($file1, $path);
            $datas['identity_card_back'] = static::uploadImage($file2, $path);
            $datas['identity_card_hand'] = static::uploadImage($file3, $path);
            $datas['status'] = 1;
            $datas['remark'] = '';
            RealNameCertification::create($datas);

            return response()->apiJson(0);
        } catch (Exception $e) {
            myLog('wx-profile-certification-error', ['用户:' => $user->id ?? '', '失败:' => $e->getMessage()]);
            return response()->apiJson(1003);
        }
    }
}
