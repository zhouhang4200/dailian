<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAssetFlow extends Model
{
    public $fillable = [
        'user_id',
        'type',
        'sub_type',
        'trade_no',
        'amount',
        'balance',
        'frozen',
        'remark' ,
    ];
}
