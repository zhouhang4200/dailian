<?php

namespace App\Http\Controllers\Back;

use App\Models\Region;
use App\Http\Controllers\Controller;

/**
 * Class RegionController
 * @package App\Http\Controllers\Back
 */
class RegionController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('back.region.index', [
            'regions' => Region::condition(request()->all())->paginate(),
        ]);
    }


}
