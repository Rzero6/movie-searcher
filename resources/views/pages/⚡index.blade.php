<?php

use Livewire\Component;
use App\Services\OmdbService;
use Livewire\Attributes\Url;

new class extends Component {
    #[Url(as: 'search')]
    public string $query = '';
    public string $input = '';
    public int $page = 1;
    public array $movies = [];
    public bool $hasMore = true;
    public int $resultsFound = 0;

    public function mount(OmdbService $omdb)
    {
        $this->input = $this->query;
        if (trim($this->query) !== '') {
            $this->loadMore($omdb);
        }
    }

    public function search(OmdbService $omdb)
    {
        if (trim($this->input) === '') {
            return;
        }
        $this->query = $this->input;
        $this->reset(['page', 'movies', 'hasMore', 'resultsFound']);
        $this->loadMore($omdb);
    }

    public function loadMore(OmdbService $omdb)
    {
        if (!$this->hasMore || trim($this->query) === '') {
            return;
        }

        $response = $omdb->search($this->query, $this->page);
        if (empty($response['Search'])) {
            $this->hasMore = false;
            return;
        }

        $this->movies = [...$this->movies, ...$response['Search']];
        $this->resultsFound = $response['totalResults'];
        $this->page++;
    }

    public function render()
    {
        return $this->view()->layout('layouts.app')->title(__('messages.app_name'));
    }
};
?>

<div class="container py-4">
    <div class="row mb-4">
        <div class="col-8 col-md-10">
            <input type="text" class="form-control" placeholder= "{{__('messages.placeholder_search')}}"
                wire:model="input" wire:keydown.enter="search">
        </div>
        <div class="col-4 col-md-2">
            <button class="btn btn-primary w-100" wire:click="search" wire:loading.attr="disabled">
                {{ __('messages.search') }}
            </button>
        </div>
    </div>
    <div class="row">
        @foreach ($movies as $movie)
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <div class="card" style="cursor: pointer;"
                    onclick="window.location='{{ route('movie.detail', ['id' => $movie['imdbID']]) }}'">
                    <div style="height: 480px; overflow: hidden;">
                        <img loading="lazy"
                            src="{{ $movie['Poster'] !== 'N/A' ? $movie['Poster'] : 'https://placehold.co/320x480?text=No+Image' }}"
                            class="card-img-top h-100" style="object-fit: cover; object-position: top;"
                            alt="{{ $movie['Title'] }}" onerror="this.src='https://placehold.co/320x480?text=No+Image'">
                    </div>
                    <div class="card-body">
                        <h6 class="card-title">{{ $movie['Title'] }}</h6>
                        <small class="text-muted">{{ $movie['Year'] }}</small>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @if ($hasMore)
        <div x-data x-intersect="$wire.loadMore()" class="text-center py-4">
            <div wire:loading class="spinner-border text-primary"></div>
        </div>
    @endif

    @if (!$hasMore && count($movies) === 0 && $query)
        <p class="text-center text-muted mt-4">
            {{ __('messages.no_movies_found') }}
        </p>
    @endif
    @if ($query === '')
        <h3 class="text-center text-muted">
            {{ __('messages.hey') }}, {{ auth()->user()->username }}
        </h3>
        <p class="text-center text-muted mt-4">
            {{ __('messages.lets_search') }}
        </p>
    @endif
</div>
