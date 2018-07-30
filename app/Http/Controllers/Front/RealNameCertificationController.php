<?php

namespace App\Http\Controllers\Front;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\RealNameCertification;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * 打手实名认证
 * Class RealNameCertificationController
 * @package App\Http\Controllers\Front
 */
class RealNameCertificationController extends Controller
{
    protected static $extensions = ['png', 'jpg', 'jpeg', 'gif'];

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if (Auth::user()->isParent()) {
            $realNameCertification = RealNameCertification::where('user_id', Auth::user()->id)->with('user')->first();
            return view('front.real-name-certification.index', compact('realNameCertification'));
        } else {
            abort(404);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        if (Auth::user()->isParent()) {
            $user = Auth::user();
            // 如果已经提交过实名认证，则不能再次填写实名认证
            $has = RealNameCertification::where('user_id', $user->id)->first();
            if ($has) {
                abort(404);
            }
            return view('front.real_name_certification.create', compact('user'));
        } else {
            abort(404);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        if (Auth::user()->isParent()) {
            // 用户是否重复提交
            $user = Auth::user();
            $has = RealNameCertification::where('user_id', $user->id)->first();
            if ($has) {
                return response()->ajaxFail('您已提交过实名认证');
            }

            $data = $request->data ?? [];
            $data['user_id'] = $user->id;
            $data['status'] = 1;
            $data['remark'] = '';

            if (RealNameCertification::create($data)) {
                return response()->ajaxSuccess('提交成功，请等待后台验证');
            }
            return response()->ajaxFail('提交失败，请重新填写');
        } else {
            abort(404);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request)
    {
        if (Auth::user()->isParent()) {
            $realNameCertification = RealNameCertification::where('user_id', Auth::user()->id)->first();
            // 不存在记录，或不是自己的记录，或已经通过的,请求修改路由跳到404
            if ($request->id != $realNameCertification->id || empty($realNameCertification) || $realNameCertification->status == 2){
                abort(404);
            }

            return view('front.real_name_certification.edit', compact('realNameCertification'));
        } else {
            abort(404);
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function update(Request $request)
    {
        if (Auth::user()->isParent()) {
            // 用户是否已经通过实名认证
            $user = Auth::user();
            $realNameCertification = RealNameCertification::where('user_id', Auth::user()->id)->first();
            // 不存在记录，或不是自己的记录，或已经通过的,请求修改路由跳到404
            if ($request->id != $realNameCertification->id || empty($realNameCertification) || $realNameCertification->status == 2){
                abort(404);
            }

            $data = $request->data ?? [];
            $data['user_id'] = $user->id;
            $data['status'] = $realNameCertification->status;
            $data['remark'] = $realNameCertification->remark;

            if ($realNameCertification->update($data)) {
                return response()->ajaxSuccess('修改成功，请等待后台认证');
            }
            return response()->ajaxFail('修改失败，请重新填写');
        } else {
            abort(404);
        }
    }

    /**
     * 图片上传
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function imageUpdate(Request $request)
    {
        if ($request->hasFile('file')) {

            $file = $request->file('file');

            $path = public_path("/resources/ident/".date('Ymd')."/");

            $imagePath = $this->uploadImage($file, $path);

            return response()->json(['status' => 1, 'path' => $imagePath]);
        }
    }

    /**
     * @param UploadedFile $file
     * @param $path
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function uploadImage(UploadedFile $file, $path)
    {
        // 获取可传输的图片类型
        $extension = $file->getClientOriginalExtension();

        if ($extension && ! in_array(strtolower($extension), static::$extensions)) {
            return response()->json(['status' => 2, 'path' => $imagePath]);
        }
        // 判断上传是否为空
        if (! $file->isValid()) {
            return response()->json(['status' => 2, 'path' => $imagePath]);
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
