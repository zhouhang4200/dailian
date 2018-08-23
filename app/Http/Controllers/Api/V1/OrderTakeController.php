<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\GameLevelingOrder;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

/**
 * Class OrderTakeController
 * @package App\Http\Controllers\Api\V1
 */
class OrderTakeController extends Controller
{
    /**
     * @return mixed
     */
    public function index()
    {
        $orders = GameLevelingOrder::searchCondition(array_merge([
            'take_parent_user_id' => auth()->user()->parent_id],
            request()->except('take_parent_user_id')))
            ->select([
                'trade_no',
                'status',
                'game_name',
                'region_name',
                'server_name',
                'game_role',
                'title',
                'game_leveling_type_name',
                'security_deposit',
                'efficiency_deposit',
                'amount',
                'take_order_password',
            ])
            ->paginate(request('page_size', 20));

        $orderList = [];
        foreach ($orders->items() as $key => $item) {
            $itemArr = $item->toArray();

            $itemArr['top'] = empty($itemArr['take_order_password']) ? 2 : 1;
            $itemArr['private'] = empty($itemArr['take_order_password']) ? 2 : 1;
            $itemArr['status_describe'] = GameLevelingOrder::$statusDescribe[$itemArr['status']];

            unset($itemArr['id']);
            unset($itemArr['status']);
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

        $detail = GameLevelingOrder::searchCondition(array_merge([
            'take_parent_user_id' => auth()->user()->parent_id,
            ], request()->except('take_parent_user_id')))
            ->select([
                'trade_no',
                'status',
                'game_name',
                'region_name',
                'server_name',
                'game_account',
                'game_password',
                'game_role',
                'title',
                'game_leveling_type_name',
                'security_deposit',
                'efficiency_deposit',
                'amount',
                'explain',
                'requirement',
                'created_at',
                'take_at',
                'complete_at',
            ])
            ->get()
            ->toArray();

        if (!isset($detail[0])) {
            return response()->apiJson(0, $detail[0]);
        }

        $detail[0]['status_describe'] = GameLevelingOrder::$statusDescribe[$detail[0]['status']];

        unset($detail[0]['id']);
        unset($detail[0]['status']);

        return response()->apiJson(0, $detail[0]);
    }
}
