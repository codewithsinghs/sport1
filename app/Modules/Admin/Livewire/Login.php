<?php

namespace App\Modules\Admin\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.auth')]
class Login extends Component
{
    public string $email = '';

    public string $password = '';

    public bool $remember = false;

    public function login(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $throttleKey = Str::lower($this->email).'|'.request()->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $this->addError(
                'email',
                'Too many sign-in attempts. Please try again in '.RateLimiter::availableIn($throttleKey).' seconds.'
            );

            return;
        }

        if (! Auth::attempt([
            'email' => $this->email,
            'password' => $this->password,
            'is_active' => true,
        ], $this->remember)) {
            RateLimiter::hit($throttleKey, 60);

            $this->addError('email', 'These credentials do not match an active account.');

            return;
        }

        RateLimiter::clear($throttleKey);

        if (request()->hasSession()) {
            request()->session()->regenerate();
        }

        $this->redirectIntended(route('admin.dashboard'));
    }

    public function render()
    {
        return view('admin::livewire.login');
    }
}
