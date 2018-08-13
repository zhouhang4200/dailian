<?php

namespace App\Http\Controllers\Back\Rbac;

use Cache;
use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * 商户权限之权限的增删改查
 * Class PermissionController
 * @package App\Http\Controllers\Back\Rbac
 */
class PermissionController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $permissions = Permission::paginate(10);

        if ($request->ajax()) {
            return response()->json(view()->make('back.rbac.permission.list', [
                'permissions' => $permissions,
            ])->render());
        }
        return view('back.rbac.permission.index', compact('permissions'));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $data['name'] = $request->data['name'];
        $data['alias'] = $request->data['alias'];
        $data['module_name'] = $request->data['module_name'];

        Permission::create($data);
        // 创建默认角色
        $role = Role::where('user_id', 0)->first();

        if (! $role) {
            $role = Role::create([
                'user_id' => 0,
                'name' => 'default',
                'alias' => '商户默认所有权限',
            ]);
        }
        // 建立关联关系
        $allPermissionIds = Permission::pluck('id')->toArray();
        $role->permissions()->sync($allPermissionIds);

        // 获取该权限相关的所有角色,再找角色对应的所有用户
        $userIds = $role->users->pluck('id')->toArray();

        if (isset($userIds) && count($userIds) > 0) {
            foreach ($userIds as $userId) {
                Cache::forget('permission:user:'.$userId);
            }
        }

        return response()->ajaxSuccess('添加成功!');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function  update(Request $request)
    {
        $newModule = Permission::find($request->id);

        $newModule->name = $request->data['name'];
        $newModule->alias = $request->data['alias'];
        $newModule->module_name = $request->data['module_name'];
        $newModule->save();

        // 创建默认角色
        $role = Role::where('user_id', 0)->first();

        if (! $role) {
            $role = Role::create([
                'user_id' => 0,
                'name' => 'default',
                'alias' => '商户默认所有权限',
            ]);
        }
        // 建立关联关系
        $allPermissionIds = Permission::pluck('id')->toArray();
        $role->permissions()->sync($allPermissionIds);

        // 获取该权限相关的所有角色,再找角色对应的所有用户
        $userIds = $role->users->pluck('id')->toArray();

        if (isset($userIds) && count($userIds) > 0) {
            foreach ($userIds as $userId) {
                Cache::forget('permission:user:'.$userId);
            }
        }

        return response()->ajaxSuccess('修改成功!');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function delete(Request $request)
    {
        $permission = Permission::find($request->id);
        // 获取该权限相关的所有角色,再找角色对应的所有用户
        $userIds = $permission->roles->flatMap(function ($role) {
            return $role->users->pluck('id');
        })->flatten()->toArray();

        foreach ($userIds as $userId) {
            Cache::forget('permission:user:'.$userId);
        }
        // 删除角色下面的权限
        $permission->roles()->detach();
        // 删除自己
        $permission->delete();

        return response()->ajaxSuccess('删除成功!');
    }
}
