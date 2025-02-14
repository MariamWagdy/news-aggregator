<?php

namespace App\Services\Scrapers;

use Illuminate\Support\Str;

class NewsAPIScraper extends BaseScraper
{
    protected string $apiKey;

    public function __construct(string $apiKey)
    {
        parent::__construct();
        $this->apiKey = $apiKey;
    }

    public function fetchNews(): array
    {
        $response = $this->makeRequest('https://newsapi.org/v2/top-headlines', [
            'apiKey' => $this->apiKey,
            'language' => 'en',
            'pageSize' => 50,
        ]);

        if (!$response->successful()) {
            return [];
        }

        return $this->transformNews($response->json()['articles']);
    }

    private function transformNews(array $articles): array
    {
        return array_map(fn($article) => [
            'source_article_id' => $this->generateUniqueId($article),
            'title' => $article['title'],
            'source' => $article['source']['name'],
            'author' => $article['author'] ?? null,
            'description' => $article['description'] ?? null,
            'content' => $article['content'] ?? null,
            'url' => $article['url'],
            'image_url' => $article['urlToImage'] ?? null,
            'category' => 'General',   // no categories retrieved from the api
            'published_at' => $article['publishedAt'] ?? now(),
        ], $articles);
    }


    private function generateUniqueId(array $article): string
    {
        $sourceId = $article['source']['id'] ?? Str::slug($article['source']['name'] ?? 'unknown-source');
        $timestamp = isset($article['publishedAt']) ? strtotime($article['publishedAt']) : time();
        $titleSlug = Str::slug($article['title'] ?? 'untitled');
        return "{$sourceId}/{$timestamp}/{$titleSlug}";
    }
}

