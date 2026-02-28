<?php

use Livewire\Component;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

new class extends Component {
    public string $currentLocale;

    public array $availableLocales = [
        'en' => 'English',
        'id' => 'Indonesia',
    ];

    public function mount(): void
    {
        $this->currentLocale = Session::get('locale', config('app.locale'));
    }

    public function switchLanguage(string $locale): void
    {
        if (!array_key_exists($locale, $this->availableLocales)) {
            return;
        }

        Session::put('locale', $locale);
        App::setLocale($locale);
        $this->currentLocale = $locale;

        $this->redirect(request()->header('Referer') ?? '/');
    }

    public function render()
    {
        return $this->view();
    }
};
?>

<div class="dropdown">
    <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
      <i class="bi bi-translate"></i>  {{ $availableLocales[$currentLocale] }}
    </button>
    <ul class="dropdown-menu">
        @foreach ($availableLocales as $locale => $label)
            <li>
                <button wire:click="switchLanguage('{{ $locale }}')"
                    class="dropdown-item {{ $currentLocale === $locale ? 'active' : '' }}">
                    {{ $label }}
                </button>
            </li>
        @endforeach
    </ul>
</div>
