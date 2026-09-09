<div class="position-relative">
    <button type="button" class="btn btn-light position-relative border-0" wire:click="toggleDropdown" aria-label="Notifications">
        <i class="bi bi-bell fs-5"></i>
        @if($unreadCount > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ $unreadCount }}
                <span class="visually-hidden">unread notifications</span>
            </span>
        @endif
    </button>

    @if($showDropdown)
        <div class="position-absolute end-0 top-100 mt-2 shadow-lg border-0 rounded-3 bg-white" style="width: 360px; max-height: 480px; overflow-y: auto; z-index: 1050;">
            <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                <h6 class="mb-0 fw-bold">Powiadomienia</h6>
                @if($unreadCount > 0)
                    <button type="button" class="btn btn-sm btn-link text-primary p-0" wire:click="markAllAsRead">
                        Oznacz wszystkie jako przeczytane
                    </button>
                @endif
            </div>

            <div class="list-group list-group-flush">
                @forelse($notifications as $notification)
                    <div class="list-group-item {{ $notification->read ? '' : 'bg-light' }} d-flex justify-content-between align-items-start gap-2">
                        <div class="min-w-0">
                            <div class="fw-semibold small {{ $notification->read ? 'text-muted' : 'text-dark' }}">
                                {{ $notification->message }}
                            </div>
                            <div class="text-muted small">
                                {{ $notification->created_at?->diffForHumans() }}
                            </div>
                        </div>
                        @if(!$notification->read)
                            <button type="button" class="btn btn-sm btn-outline-primary flex-shrink-0" wire:click="markAsRead({{ $notification->id }})">
                                <i class="bi bi-check2"></i>
                            </button>
                        @endif
                    </div>
                @empty
                    <div class="text-muted text-center py-4">
                        <i class="bi bi-bell-slash d-block mb-2 fs-3"></i>
                        Brak powiadomień
                    </div>
                @endforelse
            </div>
        </div>
    @endif
</div>