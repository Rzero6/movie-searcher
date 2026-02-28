<?php

namespace App\Http\Controllers;

use App\Models\FavoriteMovie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DetailMovieController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'imdb_id' => 'required|string'
        ]);

        $response = Http::get('https://www.omdbapi.com/', [
            'apikey' => config('services.omdb.key'),
            'i' => $request->imdb_id,
        ]);

        $movie = $response->json();

        if (($movie['Response'] ?? 'False') === 'False') {
            abort(404, 'Movie not found');
        }

        $favoriteMovie = FavoriteMovie::where('imdb_id', $request->imdb_id)->first();
        $favoriteMovieId = $favoriteMovie ? $favoriteMovie->imdb_id : null;

        return view('detailmovie', compact('movie', 'favoriteMovieId'));
    }
}
