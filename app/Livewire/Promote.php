<?php

namespace App\Livewire;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class Promote extends Component
{
    public Model $model;
    public bool $isPromoted = false;

    /**
     * en: Mount the component and set the initial promotion status.
     * de: Lade die Komponente und setze den anfänglichen Werbestatus.
     */
    public function mount(Model $model)
    {
        $this->model = $model;
        $this->updatePromotionStatus();
    }

    /**
     * en: Update the promotion status based on the database.
     * de: Aktualisiere den Werbestatus basierend auf der Datenbank.
     */
    #[On('promotionUpdated')]
    public function updatePromotionStatus(): void
    {
        // The `isPromoted` method is already on the Offer model
        $this->isPromoted = $this->model->isPromoted();
    }

    /**
     * en: Open the promotion form in a modal.
     * de: Öffne das Werbeformular in einem Modal.
     */
    public function openPromoteForm()
    {
        
        $this->dispatch('openModal', 
                view: 'promote-form', 
                title: 'Promuj swoją treść', 
                params: [
                    'modelId' => $this->model->id, 
                    'modelClass' => get_class($this->model)
                    ]);
    }

    public function render()
    {
        return view('livewire.promote');
    }
}