<?php

namespace App\Livewire\Business;

use App\Models\Business;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;

class Create extends Component
{
    public string $name = '';
    public string $address = '';
    public string $description = '';
    public string $phone = '';
    public string $website = '';

    protected array $rules = [
        'name' => 'required|min:3|max:255',
        'address' => 'required|min:3|max:255',
        'description' => 'required|min:10',
        'phone' => 'nullable|max:20',
        'website' => 'nullable|url|max:255',
    ];

    public function save()
    {
        $this->validate();

        Business::create([
            'user_id' => Auth::id(),
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'address' => $this->address,
            'description' => $this->description,
            'phone' => $this->phone,
            'website' => $this->website,
        ]);

        session()->flash('status', 'Dziękujemy za dodanie firmy. Pojawi się ona w katalogu po zatwierdzeniu przez administratora.');

        return $this->redirect('/business');
    }

    public function render()
    {
        return view('livewire.business.create');
    }
}
