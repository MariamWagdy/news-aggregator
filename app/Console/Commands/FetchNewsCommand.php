<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NewsScrapingAPIService;

class FetchNewsCommand extends Command
{
    protected $signature = 'news:fetch';
    protected $description = 'Fetch news from all configured sources';
    protected NewsScrapingAPIService $newsScraper;

    public function __construct(NewsScrapingAPIService $newsScraper)
    {
        parent::__construct();
        $this->newsScraper = $newsScraper;
    }

    public function handle()
    {
        $this->info("Starting news fetch...");

        try {
            $this->newsScraper->fetchNewsFromAllSources();
            $this->info("News fetching completed successfully.");
        } catch (\Exception $e) {
            $this->error("Error during news fetching: " . $e->getMessage());
        }
    }
}


