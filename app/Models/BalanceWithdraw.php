<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BalanceWithdraw extends Model
{
    public $fillable = [
        'user_id',
        'trade_no',
        'amount',
        'real_name',
        'bank_card',
        'bank_name' ,
        'status' ,
        'remark' ,
    ];
}
