<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    @livewireStyles
</head>

<body>
    @auth
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
            <div class="container">
                <a class="navbar-brand fw-bold" href="{{ route('index') }}">
                    <i class="bi bi-film"></i> {{ __('messages.app_name') }}
                </a>
                <div class="d-flex align-items-center gap-3">
                    <button onclick="window.location='{{ route('index') }}'" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-house-fill"></i> {{ __('messages.home') }}
                    </button>
                    <button onclick="window.location='{{ route('favorite.movie') }}'" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-heart-fill"></i> {{ __('messages.favorites') }}
                    </button>
                    <livewire:language-switcher />
                    <form method="POST" action="{{ route('logout') }}" class="mb-0">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('{{ __('auth.logout_confirm') }}')">
                            <i class="bi bi-box-arrow-right"></i> {{ __('auth.logout') }}
                        </button>
                    </form>
                </div>
            </div>
        </nav>
    @endauth

    {{ $slot }}
    <script>
        window.addEventListener('notify', event => {
            alert(event.detail.message)
        })
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @livewireScripts
</body>

</html>
