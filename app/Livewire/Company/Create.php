<?php

namespace App\Livewire\Company;

use App\Models\Company;
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
        'subdomain' => 'required|min:3|max:50|alpha_dash|unique:companies,subdomain',
    ];
   
    public function updatedName(string $value): void
    {
        $this->subdomain = Str::slug($value);
    }

    public function save()
    {
        $this->validate();

        $company = Company::create([
            'name' => $this->name,
            'subdomain' => $this->subdomain,
            'description' => 'Default description for ' . $this->name
        ]);

        $company->users()->attach(Auth::id(), ['owner' => true]);
        session()->flash('status', 'Biznes został dodany!');

        $this->reset(['name', 'subdomain']);

        return $this->redirect(route('user.profile'));
    }

    public function render()
    {
        return view('livewire.company.form',
            ['isEdit' => false]
        );
    }
}
