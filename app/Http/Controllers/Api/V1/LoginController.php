<?php

namespace App\Http\Controllers\Api\V1;

use Auth;
use Hash;
use Exception;
use GuzzleHttp\Client;
use Carbon\Carbon;
use App\Models\User;
use App\Models\BlockadeAccount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    /**
     *  小程序登录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws Exception
     */
    public function login(Request $request)
    {
        try {
            // 参数缺失
            if (is_null( request('phone')) || is_null(request('password'))) {
                return response()->apiJson(1001);
            }

            $user = User::where('phone', request('phone'))->first();
            if (! $user) {
                return response()->apiJson(2006); // 用户不存在
            }

            if(Hash::check(clientRSADecrypt(request('password')), $user->password)) {
                if ($user->status == 2) {
                    return response()->apiJson(2007); // 用户被禁用
                }
                // 永久封号
                $blockade = BlockadeAccount::where('user_id', $user->parent_id)
                    ->where('type', 2)
                    ->first();

                if ($blockade) {
                    return response()->apiJson(2008); // 用户被永久封号
                }
                // 常规封号
                $blockade = BlockadeAccount::where('user_id', $user->parent_id)
                    ->where('type', 1)
                    ->latest('end_time')
                    ->first();

                if ($blockade) {
                    $time = bcsub(Carbon::parse($blockade->end_time)->timestamp, Carbon::now()->timestamp, 0);
                    if ($time > 0) {
                        $leftTime= sec2Time($time);
                        $message = '您已被封号，封号原因：'.$blockade->reason.'，封号时间：'.$blockade->start_time.'至'.$blockade->end_time.' ，剩余时长：'.$leftTime.'，如有异议请联系客服。';

                        return response()->apiJson(2009); // 普通封号
                    }
                }

                $data = [
                    'name' => $user->name,
                    'age' => $user->age,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'wechat' => $user->wechat,
                    'qq' => $user->qq,
                    'avatar' => asset($user->avatar),
                    'status' => $user->status,
                    'has_openid' => $user->wechat_open_id ? 1 : 0,
                    'is_parent' => $user->isParent() ? 1 : 0,
                    'token' => $user->createToken('WanZiXiaoChengXu')->accessToken,
                ];
                return response()->apiJson(0, $data);
            } else {
                return response()->apiJson(2001);
            }
        } catch (Exception $e) {
            myLog('wx-login-error', ['失败原因：' => $e->getMessage(), $e->getFile(), $e->getLine()]);
            return response()->apiJson(1003);
        }
    }

    /**
     *  获取小程序JS_code
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function code(Request $request)
    {
        try {
            // 参数缺失
            if (is_null( request('code'))) {
                return response()->apiJson(1001);
            }

            $user = Auth::user();

            $client = new Client();

            $response = $client->request('GET', "https://api.weixin.qq.com/sns/jscode2session?appid=".config('pay.wechat.miniapp_id')."&secret=".config('pay.wechat.app_secret')."&js_code=".request('code')."&grant_type=authorization_code");
            $result =  $response->getBody()->getContents();
            $result = json_decode($result, true);

            if (is_array($result) && isset($result['openid'])) {
                $openId = $result['openid'];
                $sessionKey = $result['session_key'];
                $user->wechat_open_id = $openId;
                $user->save();

                $data['openid'] = $openId;

                return response()->apiJson(0, $data);
            } elseif (is_array($result) && isset($result['errcode'])) {
                return response()->json(['code' => 2012, 'message' => $result['errmsg']]);
            }
            return response()->apiJson(2012);
        } catch (Exception $e) {
            myLog('wx-code-error', ['失败原因：' => $e->getMessage()]);
            return response()->apiJson(1003);
        }
    }
}
