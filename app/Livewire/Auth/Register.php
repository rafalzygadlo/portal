<?php

namespace App\Livewire\Auth;

use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Faker\Factory;

class Register extends Component
{
    public string $company_name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    protected $rules = [
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
    ];

    public function register()
    {
        $validatedData = $this->validate();

        DB::transaction(function () use ($validatedData) {
            
            $user = User::create([
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'first_name' => Factory::create()->firstName,
                'last_name' => Factory::create()->lastName
            ]);
           
            
            Auth::guard('user')->login($user);
        });

        return redirect()->route('user.profile');
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
