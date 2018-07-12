<?php

namespace App\Http\Controllers\Front;

use Auth;
use Cache;
use App\Models\User;
use App\Models\Role;
use App\Models\UserRole;
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

    public function groupCreate()
    {

    }

    public function groupStore()
    {

    }

    public function groupEdit()
    {

    }

    public function groupUpdate()
    {

    }

    public function groupDelete()
    {

    }
}
