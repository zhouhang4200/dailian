<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use App\Models\Permission as PermissionModel;

class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {
        if (Auth::guest()) {
            abort(403);
        }

        $permissions = is_array($permission)
            ? $permission
            : explode('|', $permission);

        foreach ($permissions as $permission) {
            // 获取该登录的账号所有的权限
            $permissionIds = Auth::user()->getUserPermissions()->pluck('id')->toArray();
            // 获取当前登录权限
            $permissionId = PermissionModel::where('name', $permission)->value('id');

            if (in_array($permissionId, $permissionIds)) {
                return $next($request);
            }
        }

        if (request()->ajax()) {
            return response()->ajaxFail('您未开通相应权限');
        }

        abort(403);
    }
}
