<?php

namespace App\Livewire\Business\Resource;

use App\Models\Business;
use App\Models\Resource;
use Livewire\Component;

class Create extends Component
{
    public Business $business;
    public string $name = '';
    public string $type = 'person';
    public ?string $userId = null;
    public $employees;

    public function mount(Business $business)
    {
        $this->business = $business;
        $this->employees = $this->business->employees()->get();
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:person,facility,equipment',
            'userId' => 'nullable|required_if:type,person|exists:users,id',
        ]);

        $this->business->resources()->create([
            'name' => $this->name,
            'type' => $this->type,
            'user_id' => $this->type === 'person' ? $this->userId : null,
        ]);

        session()->flash('success', 'Zasób został dodany.');

        return redirect()->route('business.resources.index', $this->business);
    }

    public function render()
    {
        return view('livewire.business.resource.create')
            ->layout('layouts.business', ['business' => $this->business]);
    }
}
