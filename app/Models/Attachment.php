<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Attachment
 *
 * @package App\Models
 */
class Attachment extends Model
{
    public $fillable = [
        'name',
        'mime_type',
        'path',
    ];
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function image()
    {
        return $this->morphTo();
    }

}
