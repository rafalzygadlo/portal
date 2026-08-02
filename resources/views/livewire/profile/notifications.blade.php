<div class="container py-4">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Notifications</h5>
                @if(!empty($unreadCount))
                    <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="markAllAsRead">
                        Mark all as read
                    </button>
                @endif
            </div>
        </div>
        <div class="card-body">
            @if(!empty($notifications))
                <ul class="list-group list-group-flush">
                    @foreach($notifications as $notification)
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div>
                                <div class="fw-semibold">{{ $notification->message }}</div>
                                <div class="text-muted small">{{ $notification->created_at?->diffForHumans() }}</div>
                            </div>
                            @if(!$notification->read)
                                <button type="button" class="btn btn-sm btn-outline-primary" wire:click="markAsRead({{ $notification->id }})">
                                    Mark read
                                </button>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="text-muted text-center py-4">No notifications yet.</div>
            @endif
        </div>
    </div>
</div>
