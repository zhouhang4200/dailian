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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function balanceWithdraw()
    {
        return $this->hasOne(BalanceWithdraw::class, 'trade_no', 'trade_no');
    }
}
