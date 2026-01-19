<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Notifications extends Component
{
    public $notifications;
    public $unreadNotificationsCount;

    protected $listeners = ['notificationSent' => 'mount']; // Listen for an event to refresh notifications

    public function mount()
    {
        if (Auth::check()) {
            $this->notifications = Auth::user()->unreadNotifications()->limit(5)->get(); // Get latest 5 unread
            $this->unreadNotificationsCount = Auth::user()->unreadNotifications()->count();
        } else {
            $this->notifications = collect();
            $this->unreadNotificationsCount = 0;
        }
    }

    public function markAsRead($notificationId)
    {
        if (Auth::check()) {
            Auth::user()->notifications()->where('id', $notificationId)->first()->markAsRead();
            $this->mount(); // Refresh notifications
            $this->dispatch('notificationMarkedAsRead'); // Dispatch event for UI updates
        }
    }

    public function markAllAsRead()
    {
        if (Auth::check()) {
            Auth::user()->unreadNotifications->markAsRead();
            $this->mount(); // Refresh notifications
            $this->dispatch('notificationMarkedAsRead'); // Dispatch event for UI updates
        }
    }

    public function render()
    {
        return view('livewire.notifications');
    }
}
