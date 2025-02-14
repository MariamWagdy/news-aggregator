<?php

namespace App\Services\Scrapers;


class NYTimesScraper extends BaseScraper
{
    static string $Source = "New York Times";

    protected string $apiKey;

    public function __construct(string $apiKey)
    {
        parent::__construct();
        $this->apiKey = $apiKey;
    }

    public function fetchNews(): array
    {
        $response = $this->makeRequest("https://api.nytimes.com/svc/news/v3/content/all/all.json", [
            'api-key' => $this->apiKey,
        ]);

        if (!$response->successful()) {
            return [];
        }

        return $this->transformNews($response->json()['results']);
    }

    private function transformNews(array $articles): array
    {
        return array_map(fn($article) => [
            'source_article_id' => $article['uri'],
            'title' => $article['title'] ?? null,
            'source' => $article['source'] ?? self::$source,
            'author' => $article['byline'],   //todo check if by needed to be excluded
            'description' => $article['abstract'] ?? null,
            'content' => $article['abstract'] ?? null, // NYT doesn't provide full content via API
            'url' => $article['url'] ?? null,
            'image_url' => $article['multimedia'][0]['url'] ?? null,
            'category' => $article['section'] ?? 'General',
            'published_at' => $article['published_date'] ?? null,
        ], $articles);
    }
}

