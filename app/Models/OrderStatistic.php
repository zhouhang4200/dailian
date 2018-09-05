<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatistic extends Model
{
    public $fillable = [
        'date',
        'trade_no',
        'status',
        'game_id',
        'game_name',
        'consult_creator',
        'complain_creator',
        'user_id',
        'parent_user_id',
        'take_user_id',
        'take_parent_user_id',
        'amount',
        'security_deposit',
        'efficiency_deposit',
        'consult_complain_amount',
        'consult_complain_deposit',
        'poundage',
        'take_poundage',
        'fine',
        'take_fine',
        'order_created_at',
        'order_finished_at',
        'third',
    ];
}
