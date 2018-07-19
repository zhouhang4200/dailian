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

    public $hidden = [
        'id',
        'mime_type',
        'attachment_id',
        'attachment_type',
        'created_at',
        'updated_at',
    ];
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function image()
    {
        return $this->morphTo();
    }

}
