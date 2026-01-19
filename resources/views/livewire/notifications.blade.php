<div class="dropdown">
    <a class="nav-link" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-bell"></i>
        @if ($unreadNotificationsCount > 0)
            <span class="badge bg-danger">{{ $unreadNotificationsCount }}</span>
        @endif
    </a>

    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuLink">
        @forelse ($notifications as $notification)
            <li>
                <a class="dropdown-item @unless(empty($notification->read_at)) text-muted @endunless"
                   href="{{ $notification->data['url'] ?? '#' }}"
                   wire:click="markAsRead('{{ $notification->id }}')">
                    <strong>{{ $notification->data['comment_author_name'] ?? 'Someone' }}</strong> commented on "{{ $notification->data['article_title'] ?? 'your article' }}"
                    <div class="text-muted small">{{ $notification->created_at->diffForHumans() }}</div>
                </a>
            </li>
        @empty
            <li><span class="dropdown-item">No new notifications.</span></li>
        @endforelse
        @if ($unreadNotificationsCount > 0)
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-center text-primary" href="#" wire:click.prevent="markAllAsRead">Mark all as read</a></li>
        @endif
    </ul>
</div>
