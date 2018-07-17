<?php

namespace App\Http\Controllers\Front;

use Auth;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\RealNameCertification;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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

    public function avatarUpdate(Request $request)
    {
        if (Auth::user()->isParent()) {
            $user = Auth::user();
        } else {
            $user = Auth::user()->parent;
        }
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
}
