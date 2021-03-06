<?php

namespace App\Http\Controllers\Front\Auth;

use Carbon\Carbon;
use App\Models\User;
use App\Models\BlockadeAccount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/order/take';

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'phone';
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'geetest_challenge' => 'required',
        ], [
            'geetest_challenge.required' => '请点击按钮进行验证',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => $validator->errors()->all()[0]]);
        }

        // 对前端转输数据进行解密
        $request['password'] = clientRSADecrypt($request->password);

        // 检查账号是否被禁用（主账号设置的禁用）
        $user = User::where('phone', $request->phone)->first();

        if ($user && \Hash::check($request['password'], $user->password)) {
            // 检查有没有被禁用
            if ($user->status == 2) {
                return response()->json(['status' => 0, 'message' => '您的账号已被禁用']);
            }
            // 检查账号是否被永久封号（后台设置的封号）
            $blockaded = BlockadeAccount::where('user_id', $user->parent_id)->where('type', 2)->first();

            if ($blockaded) {
                return response()->json([
                    'status' => 3,
                    'message' => '您的账号已被封号，封号原因：'.$blockaded->reason.'，封号时间：永久，如有异议请联系客服。'
                ]);
            }
            // 检查账号是否被常规封号（后台设置的封号）
            $blockade = BlockadeAccount::where('user_id', $user->parent_id)->where('type', 1)->latest('end_time')->first();

            if ($blockade) {
                $time = bcsub(Carbon::parse($blockade->end_time)->timestamp, Carbon::now()->timestamp, 0);

                if ($time > 0) {
                    $leftTime= sec2Time($time);

                    return response()->json([
                        'status' => 3,
                        'message' => '您的账号已被封号，封号原因：'.$blockade->reason.'，封号时间：'.$blockade->start_time.'至'.$blockade->end_time.' ，剩余时长：'.$leftTime.'，如有异议请联系客服。'
                    ]);
                } else { // 到时间了解封
                    $blockade->type = 3;
                    $blockade->save();
                }
            }
        }

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            if ($this->sendLoginResponse($request)) {
                return response()->json(['status' => 1, 'message' => '成功']);
            }
        }
        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return response()->json(['status' => 0, 'message' => '账号或密码错误']);
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('front.auth.login');
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->regenerate();

        return redirect(route('login'));
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return \Auth::guard();
    }
}
