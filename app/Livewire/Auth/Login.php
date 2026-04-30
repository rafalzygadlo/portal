<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Auth;


class Login extends Component
{

    public $email = "demo@example.com";
    public $password ="demo";

    public function login()
    {
        $true = Auth::guard()->attempt(['email' => $this->email, 'password' => $this->password]);

        if($true)
        {
            return redirect()->intended('/profile');
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
