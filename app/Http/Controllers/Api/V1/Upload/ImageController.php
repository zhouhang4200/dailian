<?php

namespace App\Http\Controllers\Api\V1\Upload;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

/**
 * Class UploadController
 * @package App\Http\Controllers\Api\V1
 */
class ImageController extends Controller
{
    /**
     * @return mixed
     */
    public function index()
    {
        if (! request()->hasFile('image')) {
            return response()->apiJson(1001);
        }

        $img = request()->file('image');
        $path = $img->store(date('Ymd'), 'public');
        return response()->apiJson(0, 'storage/' . $path);
    }

    /**
     * @return mixed
     */
    public function delete()
    {
        $validator = Validator::make(request()->all(), [
            'path' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->apiJson(1001);
        }

        if (! file_exists(storage_path('app/public/' . request('path')))) {
            return response()->apiJson(8001);
        }
        unlink(storage_path('app/public/' . request('path')));

        return response()->apiJson(0);
    }
}
