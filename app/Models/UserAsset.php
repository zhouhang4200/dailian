<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserAsset
 * @package App\Models
 */
class UserAsset extends Model
{
    public $fillable = ['user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
