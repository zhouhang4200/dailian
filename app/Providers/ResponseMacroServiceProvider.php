<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class ResponseMacroServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('ajaxSuccess', function ($message = '操作成功', $content = []) {
            return response()->json(['status' => 1, 'message' => $message, 'content' => $content], 200);
        });
        Response::macro('ajaxFail', function ($message = '操作失败', $content = [], $code = 0) {
            return response()->json(['status' => $code, 'message' => $message, 'content' => $content], 200);
        });
        Response::macro('apiSuccess', function ($message = '操作成功', $data = []) {
            return response()->json(['code' => 1, 'message' => $message, 'data' => $data], 200);
        });
        Response::macro('apiFail', function ($message = '操作失败', $data = [], $code = 0) {
            return response()->json(['code' => $code, 'message' => $message, 'data' => $data], 200);
        });
        Response::macro('apiJson', function ($code, $data = []) {
            $datas = config('api.code')[$code];
            $datas['data'] = $data;
            return response()->json($datas);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
