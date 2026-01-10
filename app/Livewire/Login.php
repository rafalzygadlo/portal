<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public $email;
    public $linkSent = false;

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
        ]);

        // Generowanie podpisanego linku ważnego przez 30 minut
        $url = URL::temporarySignedRoute(
            'login.verify',
            now()->addMinutes(30),
            ['email' => $this->email]
        );

        // Wysyłka maila (używamy Mail::raw dla prostoty, w produkcji lepiej użyć Mailable)
        Mail::raw("Cześć! Kliknij w ten link, aby się zalogować do portalu Bolesławiec: \n\n $url", function ($message) {
            $message->to($this->email)
                ->subject('Twój link do logowania');
        });

        $this->linkSent = true;
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    public function render()
    {
        return view('livewire.login');
    }
}
