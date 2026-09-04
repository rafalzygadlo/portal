<?php

namespace App\Livewire\Auth;

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
            $user = Auth::user();
            $user->grantWelcomeBonusIfNeeded();

            session()->regenerate();
            // en: Redirect to the intended page, or fall back to the homepage.
            // de: Leite zur beabsichtigten Seite weiter oder falle auf die Startseite zurück.

            //Domain::defaultRedirectUrl()
            return redirect()->intended();
        }

        
        $this->addError('email', __('auth.failed'));
    }

    public function logout(Request $request)
    {
        // en: Log the user out and redirect to the homepage.
        // de: Melde den Benutzer ab und leite zur Startseite weiter.
        $guard = config('auth.defaults.guard', 'web');
        Auth::guard($guard)->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();


        $fallbackUrl = $request->getSchemeAndHttpHost() . '/';

        return redirect()->to($request->headers->get('referer') ?: $fallbackUrl);
    }

    public function render()
    {
        return view('livewire.auth.login');
    }

}
