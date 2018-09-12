<?php

namespace App\Http\Controllers\Front\Auth;

use DB;
use Exception;
use App\Models\User;
use App\Models\UserAsset;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        return view('front.auth.register');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'geetest_challenge' => 'required',
            'name' => 'required|string|max:255',
//            'email' => 'required|string|email|max:255',
            'phone' => 'required|string|min:11|max:11|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
//            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => bcrypt($data['password']),
            'pay_password' => bcrypt($data['password']),
        ]);
    }

    /* Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            $data['password'] = clientRSADecrypt($request->password);
            $data['password_confirmation'] = clientRSADecrypt($request->password_confirmation);
            $validator = Validator::make($data, [
                'geetest_challenge' => 'required',
                'name' => 'required|string|max:50',
//                'email' => 'required|string|email|max:100|unique:users',
                'phone' => 'required|string|size:11|unique:users',
                'password' => 'required|string|min:6|max:22|confirmed',
            ], [
                'geetest_challenge.required' => '请点击按钮进行验证',
                'phone.size' => '请填写正确的手机号',
                'phone.unique' => '手机号已被注册',
//                'email.unique' => '邮箱已被注册',
                'password.min' => '密码最少6位',
                'password.max' => '密码最大22位',
                'password.confirmed' => '两次密码输入不一致',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 0, 'message' => $validator->errors()->all()[0]]);
            }

            event(new Registered($user = $this->create($data)));

            $this->guard()->login($user);
        } catch (Exception $e) {
            return response()->json(['status' => 0, 'message' => '服务出错，请联系相关运营人员!']);
            myLog('register', ['message' => $e->getMessage()]);
        }
        DB::commit();

        return response()->json(['status' => 1, 'message' => '注册成功!']);
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

        $request->session()->invalidate();

        return redirect('/admin/login');
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
