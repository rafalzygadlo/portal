<?php

namespace App\Livewire\Admin\Company\User;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Index extends Component
{
    use AuthorizesRequests;

    public Company $company;
    public bool $showAttachModal = false;
    public bool $showCreateModal = false;
    public string $attachEmail = '';
    public string $firstName = '';
    public string $lastName = '';
    public string $email = '';
    public string $password = '';

    public function mount(Company $company): void
    {
        $this->authorize('manage', $company);
        $this->company = $company;
    }

    public function openAttachModal(): void
    {
        $this->resetErrorBag();
        $this->reset('attachEmail');
        $this->showAttachModal = true;
    }

    public function openCreateModal(): void
    {
        $this->resetErrorBag();
        $this->reset('firstName', 'lastName', 'email', 'password');
        $this->showCreateModal = true;
    }

    public function attachUser(): void
    {
        $this->authorize('manage', $this->company);
        $this->validate(['attachEmail' => 'required|email']);

        $user = User::where('email', $this->attachEmail)->first();

        if (!$user) {
            $this->addError('attachEmail', 'Sorry, user does not exist.');
            return;
        }

        if ($this->company->users()->whereKey($user->id)->exists()) {
            $this->addError('attachEmail', 'This user is already assigned to the company.');
            return;
        }

        $this->company->users()->attach($user, ['owner' => false]);
        $this->showAttachModal = false;
        $this->reset('attachEmail');
        session()->flash('success', 'User has been assigned to the company.');
    }

    public function createUser(): void
    {
        $this->authorize('manage', $this->company);
        $this->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'email' => $this->email,
            'password' => $this->password,
        ]);

        $this->company->users()->attach($user, ['owner' => false]);
        $this->showCreateModal = false;
        $this->reset('firstName', 'lastName', 'email', 'password');
        session()->flash('success', 'User has been created and assigned to the company.');
    }

    public function render()
    {
        return view('livewire.admin.company.user.index', [
            'users' => $this->company->users()->orderBy('first_name')->orderBy('last_name')->get(),
        ])->layout('layouts.admin', ['company' => $this->company]);
    }
}