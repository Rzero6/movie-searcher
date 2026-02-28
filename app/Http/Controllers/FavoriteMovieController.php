<?php

namespace App\Http\Controllers;

use App\Models\FavoriteMovie;
use Exception;
use Illuminate\Http\Request;

class FavoriteMovieController extends Controller
{

    public function index()
    {
        $movies = FavoriteMovie::all();
        return view('favorites.index', compact('movies'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'imdb_id' => 'required|string',
            'title' => 'required|string',
            'year' => 'required|string',
            'poster' => 'nullable|string',
        ]);

        $favorite = FavoriteMovie::create([
            'imdb_id' => $request->imdb_id,
            'title' => $request->title,
            'year' => $request->year,
            'poster' => $request->poster,
        ]);

        try {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Movie added to favorite',
                    'data' => $favorite,
                ]);
            }

            return back()->with(['success' => 'Movie added to favorite']);
        } catch (Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage(),
                ], 500);
            }

            return back()->with(['failed' => 'error: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $fav = FavoriteMovie::where('imdb_id', $id)->first();
        if (!$fav) {
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Movie is not found in favorite',
                ], 404);
            }

            return back()->with(['failed' => 'Movie is not found in favorite']);
        }
        $fav->delete();
        try {
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Movie removed from favorite',
                ]);
            }

            return back()->with(['success' => 'Movie removed from favorite']);
        } catch (Exception $e) {
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage(),
                ], 500);
            }

            return back()->with(['failed' => 'error ' . $e->getMessage()]);
        }
    }
}
