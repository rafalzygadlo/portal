<?php

namespace App\Livewire\Auth;

use App\Support\Domain;
use Illuminate\Http\Request;
use Livewire\Component;
use Auth;


class Login extends Component
{

    public $email = "demo@example.com";
    
    public $password ="password";

    public $remember = false;

    public function login()
    {

        $credentials = $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        $true = Auth::attempt($credentials, $this->remember);

        if($true)
        {

            session()->regenerate();
            // en: Redirect to the intended page, or fall back to the homepage.
            // de: Leite zur beabsichtigten Seite weiter oder falle auf die Startseite zurück.
            $this->dispatch('closeModal');
            $this->dispatch('loginSuccess');
            
            return redirect()->intended(Domain::defaultRedirectUrl());
        }

        // en: Add an error message if authentication fails.
        // de: Füge eine Fehlermeldung hinzu, wenn die Authentifizierung fehlschlägt.

        return back()
            ->withErrors(['email' => 'The provided credentials do not match our records.'])
            ->onlyInput('email');
        
        $this->addError('email', __('auth.failed'));
    }

    public function logout(Request $request)
    {
        
        // en: Log the user out and redirect to the homepage.
        // de: Melde den Benutzer ab und leite zur Startseite weiter.
        Auth::guard('web')->logout();

        // Unieważnienie sesji i wygenerowanie nowego tokenu CSRF
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $this->dispatch('logoutSuccess'); // To zdarzenie może być przydatne dla front-endu
        return redirect(Domain::defaultRedirectUrl());
    }

    public function render()
    {
        return view('livewire.auth.login');
    }

}
