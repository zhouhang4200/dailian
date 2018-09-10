<?php

namespace App\Http\Controllers\Back\System;

use App\Models\AdminPermission;
use App\Http\Controllers\Controller;
use App\Models\AdminPermissionGroup;

/**
 * Class AdminPermissionController
 * @package App\Http\Controllers\Back\System
 */
class AdminPermissionController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('back.system.admin-permission.index', [
            'permissions' => AdminPermission::condition(request()->all())->paginate()
        ]);
    }

    /***
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('back.system.admin-permission.create', [
            'permissionGroups' => AdminPermissionGroup::all()
        ]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
       try {
           AdminPermission::create([
               'name' => request('name'),
               'route_name' => request('route_name'),
               'admin_permission_group_id' => request('admin_permission_group_id'),
           ]);
           return redirect(route('admin.admin-permission.create'))->with('success', '添加成功');
       } catch (\Exception $exception) {
           return redirect(route('admin.admin-permission.create'))->with('fail', $exception->getMessage());
       }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit()
    {
        return view('back.system.admin-permission.edit', [
            'permission' => AdminPermission::findOrFail(request('id')),
            'permissionGroups' => AdminPermissionGroup::all()
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update($id)
    {
        try {
            AdminPermission::where('id', $id)->update([
                'name' => request('name'),
                'route_name' => request('route_name'),
                'admin_permission_group_id' => request('admin_permission_group_id'),
            ]);
            return redirect(route('admin.admin-permission.update', ['id' => $id]))->with('success', '更新成功');
        } catch (\Exception $exception) {
            return redirect(route('admin.admin-permission.update', ['id' => $id]))->with('fail', $exception->getMessage());
        }
    }
}
