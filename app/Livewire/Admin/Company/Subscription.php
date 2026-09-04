<?php

namespace App\Livewire\Admin\Company;

use App\Models\Company;
use Livewire\Component;

class Subscription extends Component
{
    public Company $company;

    public function mount(Company $company)
    {
        $this->company = $company;
    }

    public function render()
    {
        return view('livewire.admin.company.subscription')
            ->layout('layouts.admin', ['company' => $this->company]);
    }
}
