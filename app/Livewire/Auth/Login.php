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
            $this->dispatch('loginSuccess');
            $this->dispatch('showToast', __('auth.login_success'), 'success');
            return;
        }

        // en: Add an error message if authentication fails.
        // de: Füge eine Fehlermeldung hinzu, wenn die Authentifizierung fehlschlägt.
        $this->addError('email', __('auth.failed'));
    }

    public function logout()
    {
        // en: Log the user out and redirect to the homepage.
        // de: Melde den Benutzer ab und leite zur Startseite weiter.
        $this->dispatch('logoutSuccess');
        Auth::guard()->logout();
       
        return redirect()->route('main.index');
    }

    public function render()
    {
        return view('livewire.auth.login');
    }

}
