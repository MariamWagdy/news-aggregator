<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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
        return $this->belongsTo(NewsCategory::class, 'category');
    }

    public function platform()
    {
        return $this->belongsTo(NewsPlatform::class, 'platform');
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('latest', function ($query) {
            $query->orderBy('published_at', 'desc');
        });
    }

    /**
     * Scope to filter articles based on request parameters & user preferences.
     */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        $user = auth()->user();
        if ($user) {
            $preferredCategories = $user->preferredCategories()->select('news_categories.id')->pluck('id')->toArray();
            $preferredSources = $user->preferredSources()->pluck('source_name')->toArray();
            $preferredAuthors = $user->preferredAuthors()->pluck('author_name')->toArray();

            $query->when(!empty($preferredCategories), function ($query) use ($preferredCategories) {
                $query->whereIn('category', $preferredCategories);
            });

            $query->when(!empty($preferredSources), function ($query) use ($preferredSources) {
                $query->whereIn('source', $preferredSources);
            });

            $query->when(!empty($preferredAuthors), function ($query) use ($preferredAuthors) {
                $query->whereIn('author', $preferredAuthors);
            });
        }


        $query
            ->when(!empty($filters['keyword']), function ($query) use ($filters) {
                $query->where(function ($subQuery) use ($filters) {
                    $subQuery->where('title', 'LIKE', "%{$filters['keyword']}%")
                        ->orWhere('content', 'LIKE', "%{$filters['keyword']}%")
                        ->orWhere('description', 'LIKE', "%{$filters['keyword']}%");
                });
            })
            ->when(!empty($filters['from_date']), function ($query) use ($filters) {
                $query->whereDate('published_at', '>=', $filters['from_date']);
            })
            ->when(!empty($filters['to_date']), function ($query) use ($filters) {
                $query->whereDate('published_at', '<=', $filters['to_date']);
            })
            ->when(!empty($filters['category_id']), function ($query) use ($filters) {
                $query->where('category', $filters['category_id']);
            })
            ->when(!empty($filters['platform_id']), function ($query) use ($filters) {
                $query->where('platform', $filters['platform_id']);
            })
            ->when(!empty($filters['source']), function ($query) use ($filters) {
                $query->where('source', $filters['source']);
            });

        return $query;

    }

}
