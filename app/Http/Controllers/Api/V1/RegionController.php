<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Region;
use App\Http\Controllers\Controller;

/**
 * Class RegionController
 * @package App\Http\Controllers\Api\V1
 */
class RegionController extends Controller
{
    /**
     * @return mixed
     */
    public function index()
    {
        if (! request('game_id')){
            return response()->apiJson(1001);
        }

        return response()->apiJson(0, Region::select('game_id', 'id', 'name')
            ->where('game_id', request('game_id'))->get());
    }
}
