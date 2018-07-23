<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RealNameCertification extends Model
{
    protected $fillable = ['user_id', 'real_name', 'identity_card', 'identity_card_front', 'identity_card_back',
        'bank_card', 'bank_name', 'status', 'remark', 'created_at', 'updated_at'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
