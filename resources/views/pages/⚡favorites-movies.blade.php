<?php

use Livewire\Component;
use App\Services\FavoriteMoviesService;

new class extends Component {
    public int $page = 1;
    public array $movies = [];
    public bool $hasMore = true;

    public function mount(FavoriteMoviesService $service)
    {
        $this->loadMore($service);
    }

    public function loadMore(FavoriteMoviesService $service)
    {
        if (!$this->hasMore) {
            return;
        }

        $result = $service->getFavoriteMovies(12, $this->page);

        if ($result->isEmpty()) {
            $this->hasMore = false;
            return;
        }

        $this->movies = [...$this->movies, ...$result->toArray()['data']];
        $this->hasMore = $result->hasMorePages();
        $this->page++;
    }
    public function remove($omdb_id): void
    {
        try {
            app(FavoriteMoviesService::class)->removeFavoriteMovie($omdb_id);
            $this->movies = array_values(array_filter($this->movies, fn($m) => $m['imdb_id'] !== $omdb_id));
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Failed to remove movie from favorites. error: ' . $e);
        }
    }
    public function render()
    {
        return $this->view()->layout('layouts.app')->title('Favorite Movies');
    }
};
?>

<div class="container py-4">
    <h4 class="mb-4 fw-bold"><i class="bi bi-heart-fill text-danger"></i>
        {{ auth()->user()->username }}{{ __('messages.favorites_page_title') }}</h4>
    <div class="row">
        @forelse ($movies as $movie)
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <div class="card" style="cursor: pointer;"
                    onclick="window.location='{{ route('movie.detail', ['id' => $movie['imdb_id']]) }}'">

                    {{-- Floating button --}}
                    <button class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 rounded-circle"
                        wire:confirm="{{__('messages.remove_fav_confirmation')}}"
                        wire:click.stop="remove('{{ $movie['imdb_id'] }}')">
                        <i class="bi bi-heartbreak-fill"></i>
                    </button>

                    <div style="height: 480px; overflow: hidden;">
                        <img loading="lazy"
                            src="{{ $movie['poster'] !== 'N/A' && $movie['poster'] ? $movie['poster'] : 'https://placehold.co/320x480?text=No+Image' }}"
                            class="card-img-top h-100" style="object-fit: cover; object-position: top;"
                            alt="{{ $movie['title'] }}"
                            onerror="this.onerror=null; this.src='https://placehold.co/320x480?text=No+Image'">
                    </div>
                    <div class="card-body">
                        <h6 class="card-title">{{ $movie['title'] }}</h6>
                        <small class="text-muted">{{ $movie['year'] }}</small>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center text-muted mt-4">{{ __('messages.favorites_empty') }}</p>
        @endforelse
    </div>

    @if ($hasMore)
        <div x-data x-intersect="$wire.loadMore()" class="text-center py-4">
            <div wire:loading class="spinner-border text-primary"></div>
        </div>
    @endif
</div>
