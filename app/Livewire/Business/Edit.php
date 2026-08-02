<?php

namespace App\Livewire\Business;

use App\Models\Business;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Edit extends Component
{
    use AuthorizesRequests;

    public Business $business;
    public string $name = '';
    public string $description = '';

    public string $subdomain = '';

    public function mount(Business $business)
    {
        $this->authorize('update', $business);
        
        $this->business = $business;
        $this->name = $business->name;
        $this->description = $business->description ?? '';
        $this->subdomain = $business->subdomain ?? '';
    }

    public function save()
    {
        $this->authorize('update', $this->business);

        $this->validate([
            'name' => 'required|min:3|max:255',
            'description' => 'required|min:10|max:5000',
            'subdomain' => 'required|min:3|max:50|alpha_dash|unique:businesses,subdomain,' . $this->business->id,
        ]);

        $this->business->update([
            'name' => $this->name,
            'subdomain' => $this->subdomain,
            'description' => $this->description,
        ]);

        session()->flash('status', 'Biznes został zaktualizowany!');
        //return $this->redirect(route('user.profile'));
    }

    public function delete()
    {
        $this->authorize('delete', $this->business);
        $this->business->delete();
        
        session()->flash('status', 'Biznes został usunięty!');
        return $this->redirect(route('user.profile'));
    }

    public function render()
    {
        return view('livewire.business.form',
            [
                'isEdit' => true
            ]
        );
    }
}
