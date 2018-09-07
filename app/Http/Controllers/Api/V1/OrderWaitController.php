<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\GameLevelingOrder;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

/**
 * 待接单
 * Class OrderWaitController
 * @package App\Http\Controllers\Api\V1
 */
class OrderWaitController extends Controller
{
    /**
     * @return mixed
     */
    public function index()
    {
        $orders = GameLevelingOrder::searchCondition(array_merge(request()->except('status')))
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->orderBy('top_at', 'desc')
            ->paginate(request('page_size', 10));

        $orderList = [];
        foreach ($orders->items() as $key => $item) {
            $itemArr = $item->toArray();

            $itemArr['top'] = strlen($itemArr['take_order_password']) ? 1 : 2;
            $itemArr['private'] = strlen($itemArr['take_order_password']) ? 1 : 2;
            $itemArr['icon'] = $item['game']['icon'];

            unset($itemArr['id']);
            unset($itemArr['game_id']);
            unset($itemArr['game']);
            unset($itemArr['take_order_password']);

            $orderList[] = $itemArr;
        }

        return response()->apiJson(0, [
            'total' => $orders->total(),
            'total_page' => $orders->lastPage(),
            'current_page' => $orders->currentPage(),
            'page_size' => $orders->perPage(),
            'list' => $orderList
        ]);
    }

    /**
     * 订单详情
     */
    public function show()
    {
        $validator = Validator::make(request()->all(), [
            'trade_no' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->apiJson(1001);
        }

        $detail = GameLevelingOrder::searchCondition(request()->all())->select([
            'trade_no',
            'status',
            'game_id',
            'status',
            'game_name',
            'region_name',
            'server_name',
            'title',
            'game_leveling_type_name',
            'security_deposit',
            'efficiency_deposit',
            'hour',
            'day',
            'amount',
            'explain',
            'requirement',
            'take_order_password',
        ])
            ->with('game')
            ->where('status', 1)
            ->get()
            ->toArray();

        if (!isset($detail[0])) {
            return response()->apiJson(1003);
        }

        unset($detail[0]['id']);
        $detail[0]['top'] = strlen($detail[0]['take_order_password']) ? 1 : 2;
        $detail[0]['private'] = strlen($detail[0]['take_order_password']) ? 1 : 2;
        $detail[0]['icon'] = $detail[0]['game']['icon'];

        unset($detail[0]['id']);
        unset($detail[0]['game_id']);
        unset($detail[0]['game']);
        unset($detail[0]['take_order_password']);

        return response()->apiJson(0, $detail[0]);
    }
}
