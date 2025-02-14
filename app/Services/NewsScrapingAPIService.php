<?php

namespace App\Services;

use App\Factories\ScraperFactory;
use App\Models\NewsCategory;
use App\Models\NewsPlatform;
use App\Models\NewsArticle;
use Illuminate\Support\Facades\Log;

class NewsScrapingAPIService
{
    /**
     * Fetch and store news from all configured platforms.
     */
    public function fetchNewsFromAllSources(): void
    {
        $platforms = NewsPlatform::all(); // Fetch all platforms

        foreach ($platforms as $platform) {
            try {
                Log::info("Fetching news from: " . $platform->name);
                $scraper = ScraperFactory::create($platform->api_identifier);

                if (!$scraper) {
                    Log::warning("Skipping {$platform->name} (not supported or missing API key).");
                    continue;
                }

                $articles = $scraper->fetchNews();
                Log::info("Fetched " . count($articles) . " articles from {$platform->name}.");

                $this->storeNewsArticles($articles, $platform->id);


            } catch (\Exception $e) {
                Log::error("Error fetching news for {$platform->name}: " . $e->getMessage());
            }
        }
    }

    /**
     * Store a news article in the database.
     *
     * @param array $articles
     * @param int $platformId
     */

    private function storeNewsArticles(array $articles, int $platformId): void
    {
        if (empty($articles)) {
            return;
        }

        $categories = []; // Cache category names and IDs
        $bulkInsert = [];

        foreach ($articles as $data) {
            $categoryName = $data['category'] ?? 'General';

            // Cache category lookup to prevent duplicate queries
            if (!isset($categories[$categoryName])) {
                $category = NewsCategory::firstOrCreate(['name' => $categoryName]);
                $categories[$categoryName] = $category->id;
            }

            $bulkInsert[] = [
                'source_article_id' => $data['source_article_id'],
                'title' => $data['title'],
                'source' => $data['source'],
                'platform' => $platformId,
                'author' => $data['author'],
                'description' => $data['description'],
                'content' => $data['content'],
                'url' => $data['url'],
                'image_url' => $data['image_url'],
                'category' => $categories[$categoryName],
                'published_at' => $data['published_at'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Use bulk upsert to insert or update records
        NewsArticle::upsert($bulkInsert, ['source_article_id'], [
            'title', 'source', 'platform', 'author', 'description', 'content', 'url',
            'image_url', 'category', 'published_at', 'updated_at'
        ]);
    }

}

