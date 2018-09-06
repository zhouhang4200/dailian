<?php

namespace App\Http\Controllers\Front;

use DB;
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
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $phone = $request->phone;
        $station = $request->station;
        $userId = $request->userId;
        // 获取主账号分配的角色
        $userRoles = Role::where('user_id', Auth::user()->parent_id)->get();
        // 获取所有的子账号
        $children = User::where('parent_id', Auth::user()->parent_id)->get();
        // 筛选
        $filters = compact('phone', 'userId', 'station');

        //状态2是封号，1是正常
        $users = User::employeeFilter($filters)
            ->paginate(10);

        // 删除的时候页面不刷新
        if ($request->ajax()) {
            return response()->json(view()->make('front.employee.list', [
                'users' => $users,
            ])->render());
        }

        return view('front.employee.index', compact('phone', 'station', 'userId', 'users', 'userRoles', 'children'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        //获取主账号设置的所有角色
        $userRoles = Role::where('user_id', Auth::user()->parent_id)->get();

        return view('front.employee.create', compact('userRoles'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // 判断手机号位数是否正确
        $isRight = strlen($request->data['phone']);

        if ($isRight != 11) {
            return response()->json(['status' => 0, 'message' => '请输入正确的手机号']);
        }

        // 判断手机号是否唯一
        $isSingle = User::where('phone', $request->data['phone'])->withTrashed()->first();
        if ($isSingle) {
            return response()->json(['status' => 0, 'message' => '账号已存在']);
        }
        // 数据
        $data = $request->data;
        $data['password'] = bcrypt(clientRSADecrypt($request->password));
        $data['pay_password'] = bcrypt(clientRSADecrypt($request->pay_password));
        $data['parent_id'] = Auth::user()->parent_id;
        $data['avatar'] = "/front/images/default_avatar.png";
        $data['email'] = '';
        $roleIds = $request->roles ?: [];

        // 添加子账号同时添加角色
        $user = User::create($data);
        $user->roles()->sync($roleIds);
        // 清除缓存
        Cache::forget('permission:user:'.$user->id);

        return response()->json(['status' => 1, 'message' => '添加成功']);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, $id)
    {
        $user = User::find($id);
        $userRoles = Role::where('user_id', Auth::user()->parent_id)->get();

        return view('front.employee.edit', compact('userRoles', 'user'));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request)
    {
        DB::beginTransaction();
        // 子账号
        $user = User::find($request->id);
        $data = $request->data;
        unset($data['phone']);

        // 如果存在密码则修改密码
        if (clientRSADecrypt($request->password)) {
            $data['password'] = bcrypt(clientRSADecrypt($request->password));
        } else {
            unset($data['password']);
        }

        if (clientRSADecrypt($request->pay_password)) {
            $data['pay_password'] = bcrypt(clientRSADecrypt($request->pay_password));
        } else {
            unset($data['pay_password']);
        }

        try {
            // 关联到管理员-角色表
            $roleIds = $request->roles ?? [];
            $user->roles()->sync($roleIds);
            // 更新账号
            $user->update($data);
            // 清除缓存
            Cache::forget('permission:user:'.$user->id);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 0, 'message' => '修改失败']);
        }
        DB::commit();
        return response()->json(['status' => 1, 'message' => '修改成功']);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function delete(Request $request)
    {
        $user = User::find($request->id);
        // 删除该员工下面的角色和权限
        $user->roles()->detach();
        // 删除该角色并清空缓存
        $user->delete();
        // 清除缓存
        Cache::forget('permission:user:'.$user->id);

        return response()->json(['status' => 1, 'message' => '删除成功']);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function forbidden(Request $request)
    {
        $user = User::find($request->id);
        // status=2是禁用
        if ($user->status == 2) {
            $user->status = 1;
            $user->save();
            return response()->json(['status' => 1, 'message' => '账号已启用']);
        } elseif($user->status == 1) {
            $user->status = 2;
            $user->save();
            return response()->json(['status' => 1, 'message' => '账号已禁用']);
        }
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

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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
