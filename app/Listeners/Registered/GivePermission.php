<?php

namespace App\Listeners\Registered;

use App\Models\Role;
use Illuminate\Auth\Events\Registered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GivePermission
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
     * 注册成功分配默认权限
     * @param  Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        try {
            $user = $event->user;
            $role = Role::where('name', 'default')->where('user_id', 0)->first();
            $user->roles()->sync($role->id);
        } catch (Exception $e) {

        }
    }
}
