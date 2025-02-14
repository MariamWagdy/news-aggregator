<?php

namespace App\Services\Scrapers;


class GuardianScraper extends BaseScraper
{
    static string $Source = "The Guardian";
    protected string $apiKey;

    public function __construct(string $apiKey)
    {
        parent::__construct();
        $this->apiKey = $apiKey;
    }

    public function fetchNews(): array
    {

        $response = $this->makeRequest('https://content.guardianapis.com/search', [
            'api-key' => $this->apiKey,
            'format' => 'json',
            'type' => 'article',
            'show-fields' => 'headline,body,thumbnail,shortUrl',
            'show-tags' => 'contributor',
            'page-size' => 50,

        ]);
        if (!$response->successful()) {
            return [];
        }
        return $this->transformNews($response->json()['response']['results']);
    }

    private function transformNews(array $articles): array
    {
        return array_map(fn($article) => [
            'source_article_id' => $article['id'],
            'title' => $article['fields']['headline'] ?? null,
            'source' => self::$Source,
            'author' => $this->extractAuthor($article),
            'description' => $article['fields']['headline'] ?? null,
            'content' => $article['fields']['body'] ?? null,
            'url' => $article['fields']['shortUrl'] ?? null,
            'image_url' => $article['fields']['thumbnail'] ?? null,
            'category' => $article['pillarName'] ?? 'General',
            'published_at' => $article['webPublicationDate'] ?? null,
        ], $articles);
    }

    private function extractAuthor(array $article): ?string
    {
        if (!empty($article['tags'])) {
            return implode(', ', array_map(
                fn($tag) => trim(($tag['firstName'] ?? '') . ' ' . ($tag['lastName'] ?? '')),
                $article['tags']
            ));
        }
        return null;
    }

}
