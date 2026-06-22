<?php

namespace App\Livewire\Profile\Business;

use App\Models\Business;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Show extends Component
{
    use AuthorizesRequests;

    public Business $business;
    public string $name = '';
    public string $description = '';

    protected array $rules = [
        'name' => 'required|min:3|max:255',
        'description' => 'required|min:10|max:5000',
    ];

    public function mount(Business $business)
    {
        $this->authorize('update', $business);
        
        $this->business = $business;
        $this->name = $business->name;
        $this->description = $business->description ?? '';
    }

    public function save()
    {
        $this->validate();

        $this->business->update([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        session()->flash('status', 'Biznes został zaktualizowany!');
        return $this->redirect(route('user.profile'));
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
        return view('livewire.profile.business.show');
    }
}
