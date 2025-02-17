<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsPlatform extends Model
{
    use HasFactory;

    protected $table = 'news_platforms';

    protected $fillable = ['name', 'api_identifier', 'url', 'api_url'];

    public function articles(): HasMany
    {
        return $this->hasMany(NewsArticle::class, 'platform_id');
    }
}
