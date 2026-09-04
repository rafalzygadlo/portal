<?php

namespace App\Livewire\Company;

use App\Models\Company;
use Livewire\Component;

class Domain extends Component
{
    public Company $company;

    public function mount($company)
    {
        $this->company = $company;
    }

    public function render()
    {
        return view('livewire.company.domain', [
            'services' => $this->company->services()->where('is_active', true)->get(),
            'isOwner' => auth()->check() && auth()->user()->id === $this->company->user_id,
            'company' => $this->company->name,
        ])->layout('layouts.company', [
            'company' => $this->company,
        ]);
    }
}
