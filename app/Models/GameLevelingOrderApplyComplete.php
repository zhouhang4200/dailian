<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 提交验收记录
 * Class GameLevelingOrderApplyComplete
 *
 * @package App\Models
 */
class GameLevelingOrderApplyComplete extends Model
{
    public $fillable = [
        'game_leveling_order_trade_no'
    ];

    /**
     * 申请验收图片
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function image()
    {
        return $this->morphMany(Attachment::class, 'attachment', 'attachment_type', 'trade_no', 'game_leveling_order_trade_no');
    }
}
