<?php

namespace App\Factories;

use App\Services\Scrapers\NewsAPIScraper;
use App\Services\Scrapers\GuardianScraper;
use App\Services\Scrapers\NYTimesScraper;

class ScraperFactory
{
    public static function create(string $platform)
    {
        return match ($platform) {
            'NEWSAPI' => new NewsAPIScraper(env('NEWSAPI_KEY')),
            'GUARDIAN' => new GuardianScraper(env('GUARDIAN_KEY')),
            'NYT' => new NYTimesScraper(env('NYT_KEY')),
            default => throw new \Exception("Unsupported news platform: $platform"),
        };
    }
}
