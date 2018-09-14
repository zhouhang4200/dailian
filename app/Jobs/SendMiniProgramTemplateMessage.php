<?php

namespace App\Jobs;

use App\Models\User;
use EasyWeChat\Factory;
use App\Models\GameLevelingOrder;
use EasyWeChat\Kernel\Encryptor;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

/**
 * 给小程序发送模版消息
 * Class SendMiniProgramTemplateMessage
 * @package App\Jobs
 */
class SendMiniProgramTemplateMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @param GameLevelingOrder $order
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     */
    public function handle(GameLevelingOrder $order)
    {
        // 订单状态为1时发送新订单模版
        $app = Factory::miniProgram(config('wechat.mini_program.default'));

        // 获取所有不是发单人的用户
        $users = User::where('user_id', '!=', $order->parent_user_id)
            ->where('parent_id', '!=', $order->parent_user_id)
            ->whereNotNull('wechat_open_id')
            ->get();

        foreach ($users as $item) {
            $app->template_message->send([
                'touser' => $item->wechat_open_id,
                'template_id' => 'VJdkTOf5Kh8l0rThB5z0F4zhi8gXGuliHl3mOIvstOo',
                'page' => 'index',
                'form_id' => 'form-id',
                'data' => [
                    'keyword1' => $order->trade_no,  // 订单编号
                    'keyword2' => $order->title, // 商品名称
                    'keyword3' => $order->amount, // 订单金额
                    'keyword4' => $order->getStatusDescribe(), // 订单状态
                    'keyword5' => $order->created_at, // 下单时间
                ],
            ]);
        }
    }
}
