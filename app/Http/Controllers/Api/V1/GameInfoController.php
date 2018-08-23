<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Game;
use App\Models\Region;
use App\Models\Server;
use App\Http\Controllers\Controller;

/**
 * Class GameController
 * @package App\Http\Controllers\Api\V1
 */
class GameInfoController extends Controller
{
    /**
     * @return mixed
     */
    public function index()
    {
        return response()->apiJson(0, [
            'games' => Game::get(['id', 'name']),
            'regions' => Region::get(['game_id', 'id', 'name']),
            'servers' => Server::get(['region_id', 'id', 'name']),
        ]);
    }
}
