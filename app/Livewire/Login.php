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

    protected function businessSubdomain(): ?string
    {
        $host = request()->getHost();
        $domain = config('app.business_domain');

        // Sprawdzamy, czy aktualny host jest subdomeną (różni się od domeny głównej i kończy się na nią)
        if ($host !== $domain && Str::endsWith($host, $domain)) {
            // Pobieramy parametr z trasy lub wycinamy z hosta, usuwając końcową kropkę
            $subdomain = request()->route('subdomain') ?? Str::before($host, $domain);
            return rtrim($subdomain, '.');
        }

        return null;
    }

    protected function loginVerifyRouteName(): string
    {
        return $this->businessSubdomain() ? 'business.login.verify' : 'login.verify';
    }

    protected function loginRouteParams(): array
    {
        return $this->businessSubdomain() ? ['subdomain' => $this->businessSubdomain()] : [];
    }

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

        $parameters = array_merge($this->loginRouteParams(), [
            'email' => $this->email,
            'remember' => $this->remember,
            'redirect' => $intendedUrl, // Pass the redirect URL
        ]);

        // Generate a signed link valid for 5 minutes
        $url = URL::temporarySignedRoute(
            $this->loginVerifyRouteName(),
            now()->addMinutes(5),
            $parameters
        );

        // Send the email (using Mail::raw for simplicity, consider a Mailable in production)
        Mail::raw("Hello! Click this link to log in to the Boleslawiec portal: \n\n $url", function ($message) {
            $message->to($this->email)
                ->subject('Your login link');
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
            abort(403, 'Login link has expired or is invalid.');
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
