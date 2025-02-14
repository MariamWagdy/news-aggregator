<?php

namespace App\Services\Scrapers;

use Illuminate\Support\Facades\Http;

abstract class BaseScraper
{
    protected string $sslCertPath;

    public function __construct()
    {
        $this->sslCertPath = storage_path('cacert.pem');
    }

    protected function makeRequest(string $url, array $params = [])
    {
        return Http::withOptions([
            'verify' => $this->sslCertPath,
        ])->get($url, $params);
    }

    abstract public function fetchNews(): array;
}
