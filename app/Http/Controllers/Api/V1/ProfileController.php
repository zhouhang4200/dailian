<?php

namespace App\Http\Controllers\Api\V1;

use Auth;
use Carbon\Carbon;
use Hash;
use Exception;
use Redis;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use App\Services\SmSApiService;
use App\Models\RealNameCertification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * 个人中心
 * Class ProfileController
 * @package App\Http\Controllers\Api\V1
 */
class ProfileController extends Controller
{
    // 允许上传图片类型
    private static $extensions = ['png', 'jpg', 'jpeg', 'gif'];

    /**
     *  个人资料
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
            $data['email'] = $user->email;
            $data['phone'] = $user->phone;
            $data['wechat'] = $user->wechat;
            $data['qq'] = $user->qq;
            $data['avatar'] = asset($user->avatar);
            $data['status'] = $user->status;
            $data['certification_status'] = $user->realNameCertification ? $user->realNameCertification->status : 0;
            $data['balance'] = $user->userAsset ? $user->userAsset->balance : 0;
            $data['frozen'] = $user->userAsset ? $user->userAsset->frozen : 0;
            $data['signature'] = $user->signature;

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
            $avatar = request('avatar');

            // 检查后缀
            $extension = explode('.', $avatar);

            if (! in_array($extension[count($extension)-1], static::$extensions)) {
                return response()->apiJson(1008);
            }

            // 检查类型
            if(is_string($avatar)) {
                $pattern = "/".request()->server()['SERVER_NAME']."/"; // 主域名
                preg_match($pattern, $avatar, $matches);

                if ($matches) {
                    $user->avatar = str_replace(substr($avatar, 0, strpos($avatar, '.com')+4), '', $avatar); // 去除主域名之后的图片路径
                } else {
                    $user->avatar = $avatar;
                    // 检查前缀
                    $pattern = "/^\/.*/";
                    preg_match($pattern, $avatar, $arr);

                    if (! $arr) {
                        return response()->apiJson(1008);
                    }
                }
            } else {
                return response()->apiJson(1007);
            }

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
            myLog('wx-profile-payPasswordReset-error', ['用户:' => $user->id ?? '', '失败:' => $e->getMessage()]);
            return response()->apiJson(1003);
        }
    }

    /**
     *  找回支付密码
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function payPasswordRefund(Request $request)
    {
        try {
            if (is_null(request('phone')) || is_null(request('new_pay_password')) || is_null(request('verification_code'))) {
                return response()->apiJson(1001); // 参数缺失
            }

            $code = Redis::get("user:verification-code:".request('phone'));
            if (request('verification_code') != $code) {
                return response()->apiJson(1006); // 验证码错误
            }

            $user = Auth::user();

            $user->pay_password = bcrypt(request('new_pay_password'));
            $user->save();

            return response()->apiJson(0);
        } catch (Exception $e) {
            myLog('wx-profile-payPasswordRefund-error', ['用户:' => $user->id ?? '', '失败:' => $e->getMessage()]);
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

            $datas['user_id'] = $user->parent_id;
            $datas['real_name'] = request('real_name');
            $datas['identity_card'] = request('identity_card');
            $datas['bank_card'] = request('bank_card');
            $datas['bank_name'] = request('bank_name');
            $datas['identity_card_front'] = request('identity_card_front');
            $datas['identity_card_back'] = request('identity_card_back');
            $datas['identity_card_hand'] = request('identity_card_hand');
            $datas['status'] = 1;
            $datas['remark'] = '';
            RealNameCertification::create($datas);

            return response()->apiJson(0);
        } catch (Exception $e) {
            myLog('wx-profile-certification-error', ['用户:' => $user->id ?? '', '失败:' => $e->getMessage()]);
            return response()->apiJson(1003);
        }
    }

    /**
     *  实名认证详情
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function certificationShow(Request $request)
    {
        try {
            $user = Auth::user();

            $certification = RealNameCertification::where('user_id', $user->id)->first();

            if (! $certification) {
                return response()->apiJson(3005); //实名认证申请信息不存在
            }

            if (! $user->isParent()) {
                return response()->apiJson(3006); //子账号不能查看主账号实名认证信息
            }

            $data['real_name'] = $certification->real_name;
            $data['identity_card'] = $certification->identity_card;
            $data['identity_card_front'] = asset($certification->identity_card_front);
            $data['identity_card_back'] = asset($certification->identity_card_back);
            $data['identity_card_hand'] = asset($certification->identity_card_hand);
            $data['bank_card'] = $certification->bank_card;
            $data['bank_name'] = $certification->bank_name;
            $data['status'] = $certification->status;
            $data['status'] = $certification->status;
            $data['remark'] = $certification->remark;

            return response()->apiJson(0, $data);
        } catch (Exception $e) {
            myLog('wx-profile-certificationShow-error', ['用户:' => $user->id ?? '', '失败:' => $e->getMessage()]);
            return response()->apiJson(1003);
        }
    }

    /**
     *  获取手机验证码
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function verificationCode(Request $request)
    {
        try {
            if (is_null(request('phone')) || is_null(request('type'))) {
                return response()->apiJson(1001); // 参数缺失
            }

            $user = User::where('phone', request('phone'))->first();

            if (request('type') == 1) { // 注册
                if ($user) {
                    return response()->apiJson(2011); // 重复注册
                }
            }

            if (request('type') == 2 || request('type') == 3) { // 手机号没有被注册
                if (! $user) {
                    return response()->apiJson(2004); // 请填写注册时候的手机号
                }
            }

            $code = randomNumber();
            $content = "您的验证码为：".$code.",请于1分钟内正确输入。";
            $result = SmSApiService::send(request('phone'), $content);

            if ($result) {
                Redis::setex("user:verification-code:".request('phone'),60, $code); // 设置过期时间
                $count = Redis::get("user:verification-code:times:".request('phone')); // 当天发送次数

                $endOfDay = Carbon::now()->endOfDay();
                $leftSeconds = $endOfDay->diffInSeconds(); // 当前时间距离当天的秒数

                if (! $count) {
                    Redis::setex("user:verification-code:times:".request('phone'), $leftSeconds, 1);
                }

                if ($count < config('api.count')) {
                    Redis::setex("user:verification-code:times:".request('phone'), $leftSeconds, $count+1);
                } else {
                    return response()->apiJson(3007);
                }
            }

            return response()->apiJson(0);
        } catch (Exception $e) {
            myLog('wx-profile-verificationCode-error', ['失败:' => $e->getMessage()]);
            return response()->apiJson(1003);
        }
    }
}
