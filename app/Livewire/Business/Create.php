<?php

namespace App\Livewire\Business;

use App\Models\Business;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;

class Create extends Component
{
    public string $name = '';
    public string $subdomain = '';

    protected array $rules = [
        'name' => 'required|min:3|max:255',
        'subdomain' => 'required|min:3|max:50|alpha_dash|unique:businesses,subdomain',
    ];
   
    public function updatedName(string $value): void
    {
        $this->subdomain = Str::slug($value);
    }

    public function save()
    {
        $this->validate();

        $business = Business::create([
            'user_id' => Auth::id(),
            'name' => $this->name,
            'subdomain' => $this->subdomain,
            'description' => 'Default description for ' . $this->name
        ]);

        dd($business->subdomain);
        $business->users()->attach(Auth::id(), ['owner' => true]);
        session()->flash('status', 'Dziękujemy za dodanie firmy.');

        $this->reset(['name', 'subdomain']);

        return $this->redirect('/business');
    }

    public function render()
    {
        return view('livewire.business.create');
    }
}
