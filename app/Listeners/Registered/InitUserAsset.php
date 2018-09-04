<?php

namespace App\Listeners\Registered;

use App\Models\UserAsset;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class InitUserAsset
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        // 初始化用户资产
        UserAsset::create(['user_id' => $event->user->id]);
    }
}
