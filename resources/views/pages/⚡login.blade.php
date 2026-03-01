<?php

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    public string $username = '';
    public string $password = '';

    public function login()
    {
        $this->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (!Auth::attempt(['username' => $this->username, 'password' => $this->password])) {
            $this->addError('username', __('auth.invalid_login'));
            return;
        }

        session()->regenerate();
        $this->redirect(route('index'), navigate: true);
    }

    public function render()
    {
        return $this->view()->layout('layouts.app')->title('Login');
    }
};
?>

<div class="container my-5 mx-auto d-flex align-items-center justify-content-center">
    <div class="card bg-secondary-subtle border-0 shadow-lg w-50">
        <div class="card-body p-4">

            <h3 class="text-center fw-bold mb-1">{{ __('messages.app_name') }}</h3>
            <p class="text-center text-muted small mb-4">{{ __('auth.username') }}/{{ __('auth.password') }}:
                aldmic/123abc123</p>

            <div class="my-2 w-full d-flex align-items-center justify-content-center">
                <livewire:language-switcher />
            </div>

            @if ($errors->any())
                <div class="alert alert-danger py-2">{{ $errors->first() }}</div>
            @endif

            <div class="mb-3">
                <label class="form-label">{{ __('auth.username') }}</label>
                <input type="text" wire:model="username" class="form-control @error('username') is-invalid @enderror"
                    placeholder="{{__('auth.enter_user')}}" autofocus>
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('auth.password') }}</label>
                <input type="password" wire:model="password"
                    class="form-control @error('password') is-invalid @enderror" placeholder="{{__('auth.enter_pass')}}"
                    wire:keydown.enter="login">
            </div>

            <div class="d-grid mt-4">
                <button wire:click="login" wire:loading.attr="disabled" class="btn btn-primary">
                    <span wire:loading wire:target="login" class="spinner-border spinner-border-sm me-1"></span>
                    {{ __('auth.login') }}
                </button>
            </div>
        </div>
    </div>
</div>
