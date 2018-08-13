<?php

namespace App\Http\Controllers\Back\System;

use App\Models\AdminRole;
use App\Models\AdminUser;
use App\Http\Controllers\Controller;

/**
 * Class AdminUserController
 * @package App\Http\Controllers\Back\System
 */
class AdminUserController extends Controller
{
    public $roles;

    public function __construct()
    {
        $this->roles = AdminRole::get();
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('back.system.admin-user.index', [
            'admins' => AdminUser::condition(request()->all())->paginate()
        ]);
    }

    /***
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('back.system.admin-user.create', [
            'roles' => $this->roles,
        ]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        try {
            $adminUser = AdminUser::create([
                'name' => request('name'),
                'email' => request('email'),
                'password' => request('password'),
            ]);
            $adminUser->saveRoles(request('roles'));
            return redirect(route('admin.admin-user.create'))->with('success', '添加成功');
        } catch (\Exception $exception) {
            return redirect(route('admin.admin-user.create'))->with('fail', $exception->getMessage());
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit()
    {
        $adminUser = AdminUser::findOrFail(request('id'));

        return view('back.system.admin-user.edit', [
            'adminUser' => $adminUser,
            'roles' => $this->roles,
            'adminUserHasRoles' => $adminUser->cachedRoles()->pluck('id')->toArray(),
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id)
    {
        try {
            $adminUser = AdminUser::find($id);
            $adminUser->name = request('name');
            $adminUser->email = request('email');
            $adminUser->password = request('password');
            $adminUser->save();

            $adminUser->saveRoles(request('roles'));
            return redirect(route('admin.admin-user.update', ['id' => $id]))->with('success', '更新成功');
        } catch (\Exception $exception) {
            return redirect(route('admin.admin-user.update', ['id' => $id]))->with('fail', $exception->getMessage());
        }
    }
}
