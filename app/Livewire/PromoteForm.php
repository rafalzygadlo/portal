<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

class PromoteForm extends Component
{
    public Model $model;
    public int $duration = 7; // Domyślny czas trwania
    public float $cost = 0.0;

    // Definicja opcji promocji i ich cen
    public array $options = [
        7  => 7.00, 
        14 => 14.00, 
        30 => 30.00, 
    ];

    public function mount($modelId, $modelClass)
    {
        $this->model = $modelClass::findOrFail($modelId);
        $this->calculateCost();
    }

    /**
     * en: Recalculate the cost when the duration changes.
     * de: Berechne die Kosten neu, wenn sich die Dauer ändert.
     */
    public function updatedDuration(): void
    {
        $this->calculateCost();
    }

    /**
     * en: Calculate the cost based on the selected duration.
     * de: Berechne die Kosten basierend auf der gewählten Dauer.
     */
    public function calculateCost(): void
    {
        $this->cost = $this->options[$this->duration] ?? 0.0;
    }

    /**
     * en: Handle the promotion form submission.
     * de: Behandle das Absenden des Werbeformulars.
     */
    public function submitPromotion(): void
    {
        if (Gate::denies('promote', $this->model)) {
            $this->dispatch('toast', message: 'Nie masz uprawnień do promowania tej treści.', type: 'error');
            return;
        }

        if ($this->model->isPromoted()) {
            $this->dispatch('toast', message: 'Ta treść jest już promowana.', type: 'info');
            $this->dispatch('closeModal');
            return;
        }

        // W przyszłości tutaj znajdzie się logika płatności.
        // Na razie po prostu tworzymy promocję.
        $this->model->promotions()->create([
            'expires_at' => now()->addDays($this->duration),
        ]);

        $this->dispatch('toast', message: "Treść została wypromowana na {$this->duration} dni!");
        $this->dispatch('closeModal');
        $this->dispatch('promotionUpdated'); // Odświeża inne komponenty (np. przycisk)
    }

    public function render()
    {
        return view('livewire.promote-form');
    }
}