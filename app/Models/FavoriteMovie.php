<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FavoriteMovie extends Model
{
    protected $table = 'favorite_movies';
    protected $fillable = [
        'user_id',
        'imdb_id',
        'title',
        'year',
        'poster',
    ];
}
