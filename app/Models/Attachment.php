<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 附件
 * Class Attachment
 *
 * @package App\Models
 */
class Attachment extends Model
{
    public $fillable = [
        'path',
        'trade_no',
        'attachment_id',
        'attachment_type',
    ];

    public $hidden = [
        'id',
        'mime_type',
        'attachment_id',
        'attachment_type',
        'updated_at',
    ];
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function image()
    {
        return $this->morphTo();
    }


    /**
     * @param $key
     * @return string
     */
    public function getPathAttribute($key)
    {
        if ($this->attributes['path']) {
            return asset($this->attributes['path']);
        }
        return $this->attributes['path'];
    }

}
