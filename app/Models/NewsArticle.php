<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsArticle extends Model
{

    use HasFactory;

    protected $table = 'news_articles';

    protected $fillable = [
        'platform',
        'category',
        'source_article_id',
        'title',
        'source',
        'author',
        'description',
        'content',
        'url',
        'image_url',
        'published_at',
    ];

    public function category()
    {
        return $this->belongsTo(NewsCategory::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('latest', function ($query) {
            $query->orderBy('published_at', 'desc');
        });
    }

}
