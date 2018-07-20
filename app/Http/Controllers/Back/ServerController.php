<?php

namespace App\Http\Controllers\Back;

use App\Models\Server;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ServerController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('back.server.index', [
            'servers' => Server::condition(request()->all())->paginate(),
        ]);
    }
}
