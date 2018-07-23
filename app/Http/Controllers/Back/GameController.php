<?php

namespace App\Http\Controllers\Back;

use App\Models\Game;
use App\Http\Controllers\Controller;

/**
 * Class GameController
 * @package App\Http\Controllers
 */
class GameController extends Controller
{
    /**
     * 游戏列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('back.game.index', [
            'games' => Game::condition(request()->all())->paginate()
        ]);
    }
}
