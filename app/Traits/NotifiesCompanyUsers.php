<?php

namespace App\Traits;

use App\Models\Notification;

trait NotifiesCompanyUsers
{
    /**
     * Create a notification for every user associated with the current company.
     *
     * @param  string  $type  'reservation' | 'resource_booking'
     * @param  string  $message
     * @param  int  $notifiableId  ID of the Reservation or ResourceBooking
     * @param  string  $notifiableType  'Reservation' | 'ResourceBooking'
     * @param  int|null  $fromUserId
     */
    protected function notifyCompanyUsers(
        string $type,
        string $message,
        int $notifiableId,
        string $notifiableType,
        ?int $fromUserId = null
    ): void {
        $companyUserIds = $this->company->users()->pluck('users.id');

        foreach ($companyUserIds as $userId) {
            Notification::create([
                'user_id' => $userId,
                'from_user_id' => $fromUserId ?? auth()->id(),
                'type' => $type,
                'notifiable_type' => $notifiableType,
                'notifiable_id' => $notifiableId,
                'message' => $message,
                'read' => false,
            ]);
        }
    }
}