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

    /** Controls optional modal UI in the Blade view */
    public bool $showModal = false;

    protected array $rules = [
        'name' => 'required|min:3|max:255',
        'subdomain' => 'required|min:3|max:50|alpha_dash|unique:businesses,subdomain',
    ];

    public function openModal(): void
    {
        $this->resetValidation();
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
    }

    public function updatedName(string $value): void
    {
        // Convenience: auto-suggest subdomain until user starts editing it.
        if ($this->subdomain === '') {
            $this->subdomain = Str::slug($value);
        }
    }

    public function save()
    {
        $this->validate();

        Business::create([
            'user_id' => Auth::id(),
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'subdomain' => $this->subdomain,
        ]);

        session()->flash('status', 'Dziękujemy za dodanie firmy.');

        $this->reset(['name', 'subdomain']);
        $this->closeModal();

        return $this->redirect('/business');
    }

    public function render()
    {
        return view('livewire.business.create');
    }
}
