<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class Notifications extends Component
{
    public $notifications = [];
    public $unreadCount = 0;

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        if (Auth::check()) {
            $this->notifications = Auth::user()->notifications()->latest()->take(10)->get();
            $this->unreadCount = Auth::user()->notifications()->unread()->count();
        }
    }

    public function markAsRead($notificationId)
    {
        $notification = Notification::find($notificationId);
        if ($notification && $notification->user_id === Auth::id()) {
            $notification->markAsRead();
            $this->loadNotifications();
        }
    }

    public function markAllAsRead()
    {
        Auth::user()->notifications()->unread()->update(['read' => true]);
        $this->loadNotifications();
    }

    public function deleteNotification($notificationId)
    {
        Notification::destroy($notificationId);
        $this->loadNotifications();
    }

    public function render()
    {
        return view('livewire.notifications');
    }
}
