<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Spread extends Model
{
    public $timestamps = false;

    public $fillable = ['spread_user_id', 'user_id'];
}
