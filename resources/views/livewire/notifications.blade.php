<div class="dropdown">
    <a class="nav-link position-relative" href="#" role="button" id="notificationsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-bell-fill"></i>
        @if($unreadCount > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ $unreadCount }}
            </span>
        @endif
    </a>

    <div class="dropdown-menu dropdown-menu-end" style="width: 350px; max-height: 500px; overflow-y: auto;" aria-labelledby="notificationsDropdown">
        <h6 class="dropdown-header">
            Powiadomienia
            @if($unreadCount > 0)
                <button wire:click="markAllAsRead" class="btn btn-sm btn-link p-0 ms-2">
                    Oznacz wszystkie jako przeczytane
                </button>
            @endif
        </h6>

        @if(count($notifications) > 0)
            <div class="dropdown-divider"></div>
            @foreach($notifications as $notification)
                <a href="#" class="dropdown-item {{ !$notification->read ? 'bg-light' : '' }}" wire:click="markAsRead({{ $notification->id }})">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <p class="mb-1 {{ !$notification->read ? 'fw-bold' : '' }}">
                                {{ $notification->message }}
                            </p>
                            <small class="text-muted">
                                {{ $notification->created_at->diffForHumans() }}
                            </small>
                        </div>
                        <button wire:click.stop="deleteNotification({{ $notification->id }})" class="btn btn-sm btn-link text-danger p-0">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                </a>
            @endforeach
        @else
            <div class="dropdown-item text-muted text-center py-3">
                Brak powiadomień
            </div>
        @endif
    </div>
</div>
