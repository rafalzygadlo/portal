<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

abstract class AuthComponent extends Component
{
    /**
     * en: Checks if the user is authenticated. If not, it opens the login modal.
     * de: Prüft, ob der Benutzer authentifiziert ist. Wenn nicht, wird das Anmelde-Modal geöffnet.
     *
     * @param array $intendedAction Data to store in the session for post-login execution.
     * @return bool Returns true if the user is authenticated, false otherwise.
     */
    protected function checkAuth(array $intendedAction = []): bool
    {
        if (Auth::check()) {
            return true;
        }

        if (!empty($intendedAction)) {
            session()->put('intended_action', $intendedAction);
        }

        
        $this->dispatch('openModal', 'auth.login', ['title' => __('login.title')]);

        return false;
    }
}