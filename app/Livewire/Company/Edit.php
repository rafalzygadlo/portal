<?php

namespace App\Livewire\Company;

use App\Models\Company;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Edit extends Component
{
    use AuthorizesRequests;

    public Company $company;
    public string $name = '';
    public string $description = '';

    public string $subdomain = '';

    public function mount(Company $company)
    {
        $this->authorize('update', $company);
        
        $this->company = $company;
        $this->name = $company->name;
        $this->description = $company->description ?? '';
        $this->subdomain = $company->subdomain ?? '';
    }

    public function save()
    {
        $this->authorize('update', $this->company);

        $this->validate([
            'name' => 'required|min:3|max:255',
            'description' => 'nullable|min:10|max:5000',
            'subdomain' => 'required|min:3|max:50|alpha_dash|unique:companies,subdomain,' . $this->company->id,
        ]);

        $this->company->update([
            'name' => $this->name,
            'subdomain' => $this->subdomain,
            'description' => $this->description,
        ]);

        session()->flash('status', 'Biznes został zaktualizowany!');
        //return $this->redirect(route('user.profile'));
    }

    public function delete()
    {
        $this->authorize('delete', $this->company);
        $this->company->delete();
        
        session()->flash('status', 'Biznes został usunięty!');
        return $this->redirect(route('user.profile'));
    }

    public function render()
    {
        return view('livewire.company.form',
            [
                'isEdit' => true
            ]
        );
    }
}
