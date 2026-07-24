<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Auth;


class Login extends Component
{

    public $email = "demo@example.com";
    public $password ="password";

    public function login()
    {
        $true = Auth::guard()->attempt(['email' => $this->email, 'password' => $this->password]);

        if($true)
        {
            // en: Redirect to the intended page, or fall back to the homepage.
            // de: Leite zur beabsichtigten Seite weiter oder falle auf die Startseite zurück.
            $this->dispatch('closeModal');
            $this->dispatch('authChanged');
            return;
        }

        $this->addError('email', __('auth.failed'));
    }

    public function logout()
    {
        Auth::guard()->logout();
        return redirect()->route('login');
    }

    public function render()
    {
        return view('livewire.auth.login');
    }

}
