<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NewsPlatform;

class NewsPlatformsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        NewsPlatform::insert([
            ['name' => 'NewsAPI.org', 'api_identifier' => 'NEWSAPI', 'url' => 'https://newsapi.org/', 'api_url' => 'https://newsapi.org/v2/top-headlines'],
            ['name' => 'The Guardian', 'api_identifier' => 'GUARDIAN', 'url' => 'https://www.theguardian.com/international', 'api_url' => 'https://content.guardianapis.com/search'],
            ['name' => 'New York Times', 'api_identifier' => 'NYT', 'url' => 'https://www.nytimes.com/', 'api_url' => 'https://api.nytimes.com/svc/topstories/v2/'],
        ]);
    }
}
