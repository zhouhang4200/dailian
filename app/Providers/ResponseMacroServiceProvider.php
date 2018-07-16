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
        Response::macro('ajaxFail', function ($message = '操作失败', $content = []) {
            return response()->json(['status' => 0, 'message' => $message, 'content' => $content], 200);
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
