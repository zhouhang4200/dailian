<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\GameLevelingOrder;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

/**
 * 接单人
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
        $orders = GameLevelingOrder::searchCondition(request()->except('take_parent_user_id'))
            ->select([
                'game_id',
                'parent_user_id',
                'take_parent_user_id',
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
                'hour',
                'day',
                'amount',
                'take_order_password',
                'created_at',
                'take_at',
                'top',
            ])
            ->where('take_parent_user_id', auth()->user()->parent_id)
            ->orderBy('take_at', 'desc')
            ->orderBy('id', 'desc')
            ->with(['game', 'consult', 'complain'])
            ->paginate(request('page_size', 20));

        $currentUserParentId = request()->user()->parent_id;
        $orderList = [];
        foreach ($orders->items() as $key => $item) {
            $itemArr = $item->toArray();

            $itemArr['official'] = $itemArr['parent_user_id'] == config('official') ? 1 : 2;
            $itemArr['top'] = $itemArr['top'] == 0 ? 2 : 1;
            $itemArr['private'] = empty($itemArr['take_order_password']) ? 2 : 1;
            $itemArr['icon'] = $item['game']['icon'];
            $itemArr['initiator'] = $item['parent_user_id'] == $currentUserParentId ? 1 : 2;
            $itemArr['consult_initiator'] = (int) optional($item->consult)->initiator;
            $itemArr['complain_initiator'] = (int) optional($item->complain)->initiator;

            unset($itemArr['id']);
            unset($itemArr['game_id']);
            unset($itemArr['game']);
            unset($itemArr['take_order_password']);
            unset($itemArr['parent_user_id']);

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
                'hour',
                'day',
                'requirement',
                'created_at',
                'take_at',
                'complete_at',
                'parent_user_id',
                'parent_user_qq',
                'player_name',
                'player_phone',
                'player_qq',
            ])
            ->with(['consult', 'complain'])
            ->get();

        if (!isset($detail[0])) {
            return response()->apiJson(0);
        }

        $detail[0]['initiator'] = $detail[0]['parent_user_id'] == request()->user()->parent_id ? 1 : 2;
        $detail[0]['consult_initiator'] = optional($detail[0]->consult)->getConsultInitiator();;
        $detail[0]['complain_initiator'] = (int) (optional($detail[0]['complain'])['initiator']);
        $detail[0]['complain_describe'] = optional($detail[0]->complain)->getComplainDescribe();
        $detail[0]['consult_describe'] = optional($detail[0]->consult)->getConsultDescribe();
        $detail[0]['complain_result'] = optional($detail[0]->complain)->getComplainResult();
        $detail[0]['player_name'] = '号主';

        unset($detail[0]['id']);
        unset($detail[0]['consult']);
        unset($detail[0]['complain']);

        return response()->apiJson(0, $detail[0]);
    }
}
