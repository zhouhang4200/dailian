<?php

namespace App\Http\Controllers\Api\V1\Upload;

use App\Http\Controllers\Controller;

/**
 * Class UploadController
 * @package App\Http\Controllers\Api\V1
 */
class ImageController extends Controller
{

    public function index()
    {
        $img = request()->file('image');
        // 使用 store 存储文件
        $path = $img->store(date('Ymd'));
        dd($path);
    }

    public function delete()
    {

    }
}
