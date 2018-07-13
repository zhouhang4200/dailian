<?php

namespace App\Http\Controllers\Front;

use Auth;
use Cache;
use App\Models\User;
use App\Models\Role;
use App\Models\UserRole;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * 员工管理 / 岗位管理
 * Class EmployeeController
 * @package App\Http\Controllers\Front
 */
class EmployeeController extends Controller
{
    public function index()
    {

    }

    public function create()
    {

    }

    public function store()
    {

    }

    public function edit()
    {

    }

    public function update()
    {

    }

    public function delete()
    {

    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function group(Request $request)
    {
        $userRoles = Role::where('user_id', Auth::user()->parent_id)
            ->paginate(10);

        if ($request->ajax()) {
            return response()->json(view()->make('front.employee.group.list', [
                'userRoles' => $userRoles,
            ])->render());
        }
        return view('front.employee.group.index', compact('userRoles'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function groupCreate()
    {
        // 主账号
        $user = User::find(Auth::user()->parent_id);
        // 主账号清除缓存
        Cache::forget('permission:user:'.$user->id);
        // 权限模块
        $modules = Permission::pluck('module_name')->unique();
        // 所有权限
        $modulePermissions = [];
        foreach ($modules as $k => $module) {
            $modulePermissions[$module] = Permission::where('module_name', $module)->get();
        }
        return view('front.employee.group.create', compact('modulePermissions'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function groupStore(Request $request){
        // 传送勾选的权限
        if (count($request->ids) > 0) {
            $data['name'] = $request->name;
            $data['alias'] = $request->name;
            $data['user_id'] = Auth::user()->parent_id;
            $role = Role::create($data);
            // 角色-权限关联
            $role->permissions()->sync($request->ids);
            // 子账号清除缓存
            if ($role->users) {
                foreach ($role->users as $child) {
                    Cache::forget('permission:user:'.$child->id);
                }
            }
            return response()->json(['status' => 1, 'message' => '添加成功!']);
        } else {
            return response()->json(['status' => 0, 'message' => '请勾选权限!']);
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function groupEdit(Request $request, $id)
    {
        // 获取指定的岗位
        $userRole = Role::find($id);
        // 获取主账号
        $user = User::find(Auth::user()->parent_id);
        // 主账号清除缓存
        Cache::forget('permission:user:'.$user->id);
        // 权限模块
        $modules = Permission::pluck('module_name')->unique();
        // 所有权限
        $modulePermissions = [];
        foreach ($modules as $k => $module) {
            $modulePermissions[$module] = Permission::where('module_name', $module)->get();
        }
        // 子账号清除缓存
        if ($userRole->users) {
            foreach ($userRole->users as $child) {
                Cache::forget('permission:user:'.$child->id);
            }
        }
        return view('front.employee.group.edit', compact('userRole', 'modulePermissions'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function groupUpdate(Request $request)
    {
        if (count($request->permissionIds) > 0) {
            // 主账号
            $user = User::find(Auth::user()->parent_id);
            // 清除主账号缓存
            Cache::forget('permission:user:'.$user->id);
            // 修改岗位
            $userRole = Role::find($request->id);
            $userRole->user_id = $user->id;
            $userRole->alias = $request->name;
            $userRole->name = $request->name;
            $userRole->save();
            // 关联岗位-权限
            $userRole->permissions()->sync($request->permissionIds);
            // 清除子账号缓存
            if ($userRole->users) {
                foreach ($userRole->users as $child) {
                    Cache::forget('permission:user:'.$child->id);
                }
            }
            return response()->json(['status' => 1, 'message' => '修改成功!']);
        } else {
            return response()->json(['status' => 0, 'message' => '修改失败!']);
        }
    }

    public function groupDelete(Request $request)
    {
        // 获取当前岗位
        $userRole = Role::find($request->roleId);
        // 岗位删除成功之后，再删除子账号下面的权限
        $userRole->delete();
        // 删除此岗位下面所有的权限值
        $userRole->permissions()->detach();
        // 删除此角色下的用户
        $userRole->users()->detach();
        // 清除缓存
        if ($userRole->users) {
            foreach ($userRole->users as $child) {
                Cache::forget('permission:user:'.$child->id);
            }
        }
        return response()->json(['status' => 1, 'message' => '删除成功!']);
    }
}
