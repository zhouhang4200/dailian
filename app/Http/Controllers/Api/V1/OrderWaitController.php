<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\GameLevelingOrder;
use App\Http\Controllers\Controller;

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
        $orders = GameLevelingOrder::searchCondition(request()->all())
            ->select([
                'trade_no',
                'game_name',
                'region_name',
                'server_name',
                'title',
                'game_leveling_type_name',
                'security_deposit',
                'efficiency_deposit',
                'amount',
                'take_order_password',
            ])
            ->paginate(request('page_size' , 20));

        $orderList = [];
        foreach ($orders->items() as $key => $item) {
            $itemArr = $item->toArray();

            $itemArr['top'] = empty($itemArr['take_order_password']) ? 2 : 1;
            $itemArr['private'] = empty($itemArr['take_order_password']) ? 2 : 1;

            unset($itemArr['id']);
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
        $detail = GameLevelingOrder::searchCondition(request()->all())->select([
            'trade_no',
            'status',
            'game_name',
            'region_name',
            'server_name',
            'title',
            'game_leveling_type_name',
            'security_deposit',
            'efficiency_deposit',
            'amount',
            'explain',
            'requirement',
        ])->get()->toArray();

        if (!isset($detail[0])) {
            return response()->apiJson(0, $detail[0]);
        }
        $detail[0]['status_describe'] = GameLevelingOrder::$statusDescribe[$detail[0]['status']];
        unset($detail[0]['id']);
        unset($detail[0]['status']);

        return response()->apiJson(0, $detail[0]);
    }
}