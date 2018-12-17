<?php

namespace App\Http\Controllers\Front;

use App\Models\Region;
use App\Models\Server;
use App\Models\GameLevelingType;
use App\Http\Controllers\Controller;

/**
 * Class GameController
 * @package App\Http\Controllers\Front
 */
class GameController extends Controller
{

    /**
     * @return mixed
     */
    public function regions()
    {
        if (request()->ajax()) {
            return response()->ajaxSuccess('获取成功', Region::condition(['game_id' => request('game_id')])->get(['id', 'name']));
        }
    }

    /**
     * @return mixed
     */
    public function servers()
    {
        if (request()->ajax()) {
            return response()->ajaxSuccess('获取成功', Server::condition(['region_id' => request('region_id')])->get(['id', 'name']));
        }
    }

    /**
     * @return mixed
     */
    public function levelingTypes()
    {
        if (request()->ajax()) {
            return response()->ajaxSuccess('获取成功', GameLevelingType::condition(['game_id' => request('game_id')])->get(['id', 'name']));
        }
    }
}
