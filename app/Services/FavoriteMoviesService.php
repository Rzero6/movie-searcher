<?php

namespace App\Services;

use App\Models\FavoriteMovie;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class FavoriteMoviesService
{
    public function getFavoriteMovies(int $perPage = 12, int $page = 1): LengthAwarePaginator
    {
        return Cache::remember(
            "favorite_movies_user_" . auth()->id() . "_page_" . $page,
            now()->addMinutes(5),
            function () use ($perPage, $page) {
                return FavoriteMovie::where('user_id', auth()->id())
                    ->latest()
                    ->paginate($perPage, ['*'], 'page', $page);
            }
        );
    }
    public function addFavoriteMovie(array $data): FavoriteMovie
    {
        $favorite = FavoriteMovie::create([
            'user_id' => auth()->id(),
            'imdb_id' => $data['imdb_id'],
            'title'   => $data['title'],
            'year'    => $data['year'],
            'poster'  => $data['poster'],
        ]);

        $this->clearCache();

        return $favorite;
    }
    public function removeFavoriteMovie(string $imdb_id): void
    {
        FavoriteMovie::where('imdb_id', $imdb_id)
            ->where('user_id', auth()->id())
            ->delete();

        $this->clearCache();
    }

    public function isFavorite(string $imdb_id): bool
    {
        return FavoriteMovie::where('imdb_id', $imdb_id)
            ->where('user_id', auth()->id())
            ->exists();
    }

    private function clearCache(): void
    {
        $page = 1;
        while (Cache::has("favorite_movies_user_" . auth()->id() . "_page_" . $page)) {
            Cache::forget("favorite_movies_user_" . auth()->id() . "_page_" . $page);
            $page++;
        }
    }
}
