<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class ResetPassword extends Component
{
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $status = '';

    public function mount($token)
    {
        $this->token = $token;
    }

    protected $rules = [
        'email' => ['required', 'email'],
        'password' => ['required', 'min:8', 'confirmed'],
    ];

    public function resetPassword()
    {
        $this->validate();

        $response = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->forceFill([
                    'password' => bcrypt($this->password),
                ])->save();

                Auth::login($user);
            }
        );

        if ($response == Password::PASSWORD_RESET) {
            return redirect()->route('user.profile')->with('status', __($response));
        } else {
            throw ValidationException::withMessages([
                'email' => [__($response)],
            ]);
        }
    }

    public function render()
    {
        return view('livewire.auth.reset-password');
    }
}
