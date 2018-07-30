<?php

namespace App\Http\Controllers\Back\System;

use App\Models\AdminRole;
use App\Models\AdminPermission;
use App\Http\Controllers\Controller;
use function Couchbase\defaultDecoder;

/**
 * Class AdminRoleController
 * @package App\Http\Controllers\Back\System
 */
class AdminRoleController extends Controller
{

    public $permissions;

    /**
     * AdminRoleController constructor.
     */
    public function __construct()
    {
        $this->permissions = AdminPermission::with('group')->get()->groupBy('group.name');
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('back.system.admin-role.index', [
            'roles' => AdminRole::condition(request()->all())->paginate()
        ]);
    }

    /***
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('back.system.admin-role.create', [
            'permissions' => $this->permissions,
        ]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        try {
            $role = AdminRole::create([
                'name' => request('name'),
            ]);
            $role->savePermissions(request('permissions'));
            return redirect(route('admin.admin-role.create'))->with('success', '添加成功');
        } catch (\Exception $exception) {
            return redirect(route('admin.admin-role.create'))->with('fail', $exception->getMessage());
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit()
    {
        $role = AdminRole::findOrFail(request('id'));

        return view('back.system.admin-role.edit', [
            'role' => $role,
            'permissions' => $this->permissions,
            'roleHasPermissions' => $role->cachedPermissions()->pluck('id')->toArray(),
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id)
    {
        try {
            $role = AdminRole::find($id);
            $role->name = request('name');
            $role->save();

            $role->savePermissions(request('permissions'));
            return redirect(route('admin.admin-role.update', ['id' => $id]))->with('success', '更新成功');
        } catch (\Exception $exception) {
            return redirect(route('admin.admin-role.update', ['id' => $id]))->with('fail', $exception->getMessage());
        }
    }
}
