<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::livewire('/login', 'pages::login')->name('login')->middleware('guest');
Route::livewire('/', 'pages::index')->name('index')->middleware('auth');
Route::livewire('/favorite', 'pages::favorites-movies')->name('favorite.movie')->middleware('auth');
Route::livewire('/detail', 'pages::detail-movies')->name('movie.detail')->middleware('auth');
Route::post('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');
