<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPoundage extends Model
{
    public $fillable = ['user_id', 'send_poundage', 'take_poundage'];
}
