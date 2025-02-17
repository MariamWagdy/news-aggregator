<?php

namespace App\Services;

use App\Models\NewsArticle;
use Illuminate\Pagination\LengthAwarePaginator;

class ArticleService
{
    public function getArticles(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        return NewsArticle
            ::with(['category:id,name', 'platform:id,name,url'])
            ->filter($filters)->paginate($perPage);
    }
}
