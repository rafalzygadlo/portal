<?php

namespace App\Livewire\Profile\Company;

use Livewire\Component;
use App\Models\Company;

class Index extends Component
{
    public function render()
    {
        $companies = auth()->user()->ownedCompanies()->latest()->get();
        
        return view('livewire.profile.company.index', [
            'companies' => $companies
        ]);
    }
}
