<?php

use Livewire\Component;
use App\Services\OmdbService;
use App\Services\FavoriteMoviesService;
use Livewire\Attributes\Url;

new class extends Component {
    #[Url(as: 'id')]
    public string $omdb_id = '';
    public bool $found = false;
    public array $movie = [];
    public function mount(OmdbService $omdb)
    {
        if (trim($this->omdb_id) !== '') {
            $this->getDetail($omdb);
        }
    }
    public function getDetail(OmdbService $omdb)
    {
        $response = $omdb->detailMovie($this->omdb_id);
        if ($response['Response'] !== 'True') {
            return;
        }
        $this->found = true;
        $this->movie = $response;
    }
    public function store(): void
    {
        try {
            app(FavoriteMoviesService::class)->addFavoriteMovie([
                'imdb_id' => $this->movie['imdbID'],
                'title' => $this->movie['Title'],
                'year' => $this->movie['Year'],
                'poster' => $this->movie['Poster'],
            ]);
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Failed to add movie to favorites.');
        }
    }

    public function remove(): void
    {
        try {
            app(FavoriteMoviesService::class)->removeFavoriteMovie($this->omdb_id);
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Failed to remove movie from favorites.' . $e);
        }
    }

    public function render()
    {
        $isFavorite = $this->found ? app(FavoriteMoviesService::class)->isFavorite($this->omdb_id) : false;

        return $this->view(data: ['isFavorite' => $isFavorite])
            ->layout('layouts.app')
            ->title($this->found ? $this->movie['Title'] : 'Movie not found');
    }
};
?>

<div class="container w-75">

    @if ($found)
        <div class="row p-2 rounded shadow-sm">
            {{-- Poster --}}
            <div class="col-md-4 text-center">
                <img src="{{ $movie['Poster'] !== 'N/A' ? $movie['Poster'] : 'https://placehold.co/320x480?text=No+Image' }}"
                    alt="{{ $movie['Title'] }} Poster" class="w-100 rounded mb-3"
                    onerror="this.src='https://placehold.co/320x480?text=No+Image'">
            </div>

            {{-- Movie Details --}}
            <div class="col-md-8 p-2 d-flex flex-column">
                <h4 class="fw-bold">{{ $movie['Title'] }} ({{ $movie['Year'] }})</h4>
                @if ($movie['Rated'] !== 'N/A')
                    <h5>{{ $movie['Rated'] }}</h5>
                @endif
                <div class="my-2">
                    @foreach ($movie['Ratings'] as $rating)
                        <span class="badge bg-secondary me-1">{{ $rating['Source'] }}: {{ $rating['Value'] }}</span>
                    @endforeach
                </div>

                <div class="my-2 row">
                    <span class="col-12 col-md-4"><i class="bi bi-cash-stack text-success"></i>
                        {{ __('messages.box_office') }}:
                        {{ $movie['BoxOffice'] ?? 'N/A' }}</span>
                    <span class="col-12 col-md-8"><i class="bi bi-hourglass-split text-info"></i>
                        {{ __('messages.country') }}:
                        {{ $movie['Country'] ?? 'N/A' }}</span>
                </div>
                <div class="my-2 row">
                    <span class="col-12 col-md-4"><i class="bi bi-hourglass-split text-info"></i>
                        {{ __('messages.runtime') }}:
                        {{ $movie['Runtime'] ?? 'N/A' }}</span>
                    <span class="col-12 col-md-8"><i class="bi bi-tags-fill text-primary"></i>
                        {{ __('messages.genre') }}:
                        {{ $movie['Genre'] ?? 'N/A' }}</span>
                </div>
                @if ($movie['Awards'] !== 'N/A')
                    <p><i class="bi bi-award-fill text-warning"> </i>{{ $movie['Awards'] }}</p>
                @endif
                <p><strong>{{ __('messages.director') }}:</strong> {{ $movie['Director'] ?? 'N/A' }}</p>
                <p><strong>{{ __('messages.writers') }}:</strong> {{ $movie['Writer'] ?? 'N/A' }}</p>
                <p><strong>{{ __('messages.actors') }}:</strong> {{ $movie['Actors'] ?? 'N/A' }}</p>
                <p> {{ $movie['Plot'] ?? 'N/A' }}</p>

                <div class="mt-auto d-flex justify-content-end gap-2">
                    @if ($isFavorite)
                        <button wire:click="remove" class="btn btn-danger mt-3"
                            wire:confirm="{{ __('messages.remove_fav_confirmation') }}" wire:loading.attr="disabled">
                            <span wire:loading wire:target="remove"
                                class="spinner-border spinner-border-sm me-1"></span>
                            <i class="bi bi-heartbreak-fill" wire:loading.remove wire:target="remove"></i>
                            {{ __('messages.remove_to_fav') }}
                        </button>
                    @else
                        <button wire:click="store" class="btn btn-primary mt-3"
                            wire:confirm="{{ __('messages.add_fav_confirmation') }}" wire:loading.attr="disabled">
                            <span wire:loading wire:target="store" class="spinner-border spinner-border-sm me-1"></span>
                            <i class="bi bi-heart-fill" wire:loading.remove wire:target="store"></i>
                            {{ __('messages.add_to_fav') }}
                        </button>
                    @endif
                    <button type="button" class="btn btn-secondary mt-3" onclick="history.back()">
                        {{ __('messages.back') }}
                    </button>
                </div>
            </div>
        </div>
    @else
        <p class="text-center text-muted mt-4">{{ __('messages.movie_not_found') }}</p>
    @endif
</div>
