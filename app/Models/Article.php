<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'article_category_id', 'title', 'content', 'link', 'click_count',
        'sort', 'status', 'created_at', 'updated_at'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function articleCategory()
    {
        return $this->belongsTo(ArticleCategory::class);
    }

    /**
     * 查找
     * @param $query
     * @param array $filters
     * @return mixed
     */
    public static function scopeFilter($query, $filters = [])
    {
        if(isset($filters['title']) && ! empty($filters['title'])) {
            $query->where('title', $filters['title']);
        }

        return $query;
    }
}
