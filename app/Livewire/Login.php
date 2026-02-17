<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Livewire\Component;

class Login extends Component
{
    public $email;
    public $linkSent = false;
    public $remember = false;

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
        ]);

        // If user was redirected to login, use that intended URL.
        // Otherwise, use the page they were on before navigating to login.
        // Fallback to home.
        $intendedUrl = session('url.intended', url()->previous());
        if (str_contains($intendedUrl, '/login')) {
            $intendedUrl = '/';
        }

        // Generate a signed link valid for 5 minutes
        $url = URL::temporarySignedRoute(
            'login.verify',
            now()->addMinutes(5),
            [
                'email' => $this->email,
                'remember' => $this->remember,
                'redirect' => $intendedUrl // Pass the redirect URL
            ]
        );

        // Send the email (using Mail::raw for simplicity, consider a Mailable in production)
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

    public function verify(Request $request, $email)
    {
        if (! $request->hasValidSignature()) 
        {
            abort(403, 'Link logowania wygasł lub jest nieprawidłowy.');
        }

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'first_name' => Str::before($email, '@'), 
                'last_name' => '',
                'password' => Hash::make(Str::random(24)), 
            ]);

        Auth::login($user, $request->query('remember', false));

        // Redirect to the URL provided in the magic link, or fall back to home.
        return redirect($request->query('redirect', '/'));
        
    }
    public function render()
    {
        return view('livewire.login');
    }
}
