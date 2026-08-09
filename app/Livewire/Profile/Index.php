<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use App\Models\User;

class Index extends Component
{
    public User $user;
    public string $activeTab = 'overview';
    public string $referralCode = '';

    public function mount()
    {
        $this->user = auth()->user()->loadMissing([
            'ownedBusinesses',
            'offers',
            'articles',
            'polls',
            'comments',
            'favorites',
            'creditTransactions'
        ]);
        $this->referralCode = '';
    }

    public function switchTab(string $tab)
    {
        $this->activeTab = $tab;
    }

    public function applyReferralCode(): void
    {
        $user = auth()->user();

        if ($user->applyReferralCode($this->referralCode)) {
            $this->user->refresh();
            $this->dispatch('toast', message: 'Kod polecający został aktywowany. Otrzymano 50 punktów.', type: 'success');
            $this->referralCode = '';
            return;
        }

        $this->dispatch('toast', message: 'Nie można aktywować tego kodu.', type: 'error');
    }

    public function render()
    {
        return view('livewire.profile.index');
    }
}
