<?php

namespace App\Http\Controllers\Api\V1;

use Auth;
use Exception;
use Carbon\Carbon;
use App\Models\GameLevelingOrderMessage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MessageController extends Controller
{
    /**
     *  留言列表
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();

            $last7Days = Carbon::now()->subDays(7)->toDateTimeString();

            $messages = GameLevelingOrderMessage::where('to_user_id', $user->parent_id)
                ->where('created_at', '>=', $last7Days)
                ->where('created_at', '<=', Carbon::now())
                ->get();

            if ($messages->count() < 1) {
                return response()->apiJson(0);
            }

            foreach ($messages as $k => $message) {
                $data[$k]['id'] = $message->id;
                $data[$k]['game_leveling_order_trade_no'] = $message->game_leveling_order_trade_no;
                $data[$k]['content'] = $message->content;
                $data[$k]['type'] = $message->type;
                $data[$k]['status'] = $message->status;
                $data[$k]['created_at'] = $message->created_at;
            }
            return response()->apiJson(0, $data);
        } catch (Exception $e) {
            myLog('wx-message-index-error', ['用户：' => $user->id ?? '', '失败:' => $e->getMessage()]);
            return response()->apiJson(1003);
        }
    }

    /**
     *  留言详情
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function show(Request $request)
    {
        try {
            if (is_null(request('id'))) {
                return response()->apiJson(1001); // 参数缺失
            }
            $message = GameLevelingOrderMessage::find(request('id'));
            $message->status = 2;
            $message->save();

            if (! $message) {
                return response()->apiJson(5003);
            }
            $user = Auth::user();

            $data['id'] = $message->id;
            $data['game_leveling_order_trade_no'] = $message->game_leveling_order_trade_no;
            $data['content'] = $message->content;
            $data['type'] = $message->type;
            $data['status'] = $message->status;
            $data['from_username'] = $message->from_username;
            $data['created_at'] = $message->created_at;

            return response()->apiJson(0, $data);
        } catch (Exception $e) {
            myLog('wx-message-show-error', ['用户：' => $user->id ?? '', '失败:' => $e->getMessage()]);
            return response()->apiJson(1003);
        }
    }

    /**
     *  未读留言条数
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function count(Request $request)
    {
        try {
            $user = Auth::user();

            $last7Days = Carbon::now()->subDays(7)->toDateTimeString();

            $count = GameLevelingOrderMessage::where('to_user_id', $user->parent_id)
                ->where('created_at', '>=', $last7Days)
                ->where('created_at', '<=', Carbon::now()->toDateTimeString())
                ->where('status', 1)
                ->count();

            $data['count'] = $count;

            return response()->apiJson(0, $data);
        } catch (Exception $e) {
            myLog('wx-message-count-error', ['用户：' => $user->id ?? '', '失败:' => $e->getMessage()]);
            return response()->apiJson(1003);
        }
    }

    /**
     *  全部标为已读
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function readed(Request $request)
    {
        try {
            $user = Auth::user();

            $count = GameLevelingOrderMessage::where('to_user_id', $user->parent_id)
                ->update(['status' => 2]);

            return response()->apiJson(0);
        } catch (Exception $e) {
            myLog('wx-message-readed-error', ['用户：' => $user->id ?? '', '失败:' => $e->getMessage()]);
            return response()->apiJson(1003);
        }
    }
}
