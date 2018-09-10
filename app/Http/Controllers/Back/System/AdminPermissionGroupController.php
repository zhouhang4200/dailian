<?php

namespace App\Http\Controllers\Back\System;

use App\Models\AdminPermissionGroup;
use App\Http\Controllers\Controller;

/**
 * Class AdminPermissionGroupController
 * @package App\Http\Controllers\Back\System
 */
class AdminPermissionGroupController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('back.system.admin-permission-group.index', [
            'permissionGroups' => AdminPermissionGroup::condition(request()->all())->paginate()
        ]);
    }

    /***
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('back.system.admin-permission-group.create');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        try {
            AdminPermissionGroup::create([
                'name' => request('name'),
            ]);
            return redirect(route('admin.admin-permission-group.create'))->with('success', '添加成功');
        } catch (\Exception $exception) {
            return redirect(route('admin.admin-permission-group.create'))->with('fail', $exception->getMessage());
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit()
    {
        return view('back.system.admin-permission-group.edit', [
            'permissionGroup' => AdminPermissionGroup::findOrFail(request('id')),
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update($id)
    {
        try {
            AdminPermissionGroup::where('id', $id)->update([
                'name' => request('name'),
            ]);
            return redirect(route('admin.admin-permission-group.update', ['id' => $id]))->with('success', '更新成功');
        } catch (\Exception $exception) {
            return redirect(route('admin.admin-permission-group.update', ['id' => $id]))->with('fail', $exception->getMessage());
        }
    }
}
