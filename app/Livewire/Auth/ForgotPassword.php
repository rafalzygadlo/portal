<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class ForgotPassword extends Component
{
    public string $email = '';
    public string $status = '';

    protected $rules = [
        'email' => ['required', 'email'],
    ];

    public function sendResetLink()
    {
        $this->validate();

        $response = Password::sendResetLink(
            $this->only('email')
        );

        if ($response == Password::RESET_LINK_SENT) {
            $this->status = __($response);
        } else {
            throw ValidationException::withMessages([
                'email' => [__($response)],
            ]);
        }
    }

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}
