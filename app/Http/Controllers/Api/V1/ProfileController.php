<?php

namespace App\Http\Controllers\Api\V1;

use Auth;
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
}
