<?php

namespace App\Livewire\Admin\Company;

use App\Models\Company;
use App\Models\Notification;
use App\Models\Reservation;
use App\Models\ResourceBooking;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Notifications extends Component
{
    use AuthorizesRequests;

    public Company $company;
    public bool $showDropdown = false;

    public function mount(Company $company): void
    {
        $this->company = $company;
    }

    public function toggleDropdown(): void
    {
        $this->showDropdown = !$this->showDropdown;
    }

    public function markAsRead(int $notificationId): void
    {
        $notification = Notification::find($notificationId);
        if ($notification && $notification->user_id === auth()->id()) {
            $notification->markAsRead();
        }
    }

    public function markAllAsRead(): void
    {
        auth()->user()->notifications()
            ->where('type', 'reservation')
            ->where('notifiable_id', $this->company->id)
            ->unread()
            ->update(['read' => true]);
    }

    public function confirmReservation(int $notificationId): void
    {
        $this->authorize('update', $this->company);

        $notification = Notification::findOrFail($notificationId);
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        if ($notification->type === 'reservation') {
            $reservation = Reservation::where('company_id', $this->company->id)
                ->findOrFail($notification->notifiable_id);
            $reservation->update(['status' => 'confirmed']);
        } elseif ($notification->type === 'resource_booking') {
            $booking = ResourceBooking::where('company_id', $this->company->id)
                ->findOrFail($notification->notifiable_id);
            $booking->update(['status' => 'confirmed']);
        }

        $notification->markAsRead();
        session()->flash('success', 'Rezerwacja potwierdzona!');
    }

    public function cancelReservation(int $notificationId): void
    {
        $this->authorize('update', $this->company);

        $notification = Notification::findOrFail($notificationId);
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        if ($notification->type === 'reservation') {
            $reservation = Reservation::where('company_id', $this->company->id)
                ->findOrFail($notification->notifiable_id);
            $reservation->update(['status' => 'cancelled']);
        } elseif ($notification->type === 'resource_booking') {
            $booking = ResourceBooking::where('company_id', $this->company->id)
                ->findOrFail($notification->notifiable_id);
            $booking->update(['status' => 'cancelled']);
        }

        $notification->markAsRead();
        session()->flash('success', 'Rezerwacja anulowana.');
    }

    public function render()
    {
        $notifications = auth()->user()->notifications()
            ->whereIn('type', ['reservation', 'resource_booking'])
            ->where('notifiable_id', $this->company->id)
            ->latest()
            ->take(10)
            ->get();

        $unreadCount = auth()->user()->notifications()
            ->whereIn('type', ['reservation', 'resource_booking'])
            ->where('notifiable_id', $this->company->id)
            ->unread()
            ->count();

        return view('livewire.admin.company.notifications', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
    }
}