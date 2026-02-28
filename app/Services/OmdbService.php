<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class OmdbService
{
    public function search(string $query, int $page): array
    {
        return Cache::remember(
            "omdb_search_{$query}_{$page}",
            now()->addDay(),
            function () use ($query, $page) {
                return Http::get('https://www.omdbapi.com/', [
                    'apikey' => config('services.omdb.key'),
                    's' => $query,
                    'page' => $page,
                ])->json();
            }
        );
    }
    public function detailMovie(string $omdb_id)
    {
        return Cache::remember(
            "omdb_detail_{$omdb_id}",
            now()->addDay(),
            function () use ($omdb_id) {
                return Http::get('https://www.omdbapi.com/', [
                    'apikey' => config('services.omdb.key'),
                    'i' => $omdb_id,
                ])->json();
            }
        );
    }
}
