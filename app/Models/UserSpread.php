<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSpread extends Model
{
    public $fillable = ['user_id', 'spread_rate'];
}
