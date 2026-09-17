<?php

namespace App\Livewire\Company;

use App\Models\Company;
use Livewire\Component;

class Show extends Component
{
    public Company $company;

    public $currentCategory;

    public $categorySlug;

    public function mount(Company $company)
    {
        $this->company = $company;
        $this->currentCategory = $company->categories->first();
        $this->categorySlug = $this->currentCategory?->slug;
    }

    public function render()
    {
        return view('livewire.company.show');
    }
}
