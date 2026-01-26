<div class="card mb-3 shadow-sm border-0 {{ $isReply ? 'ms-4' : '' }}">
    <div class="card-body p-3">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
                <strong class="d-block">{{ $comment->user->name ?? 'Anonimowy' }}</strong>
                <small class="text-muted">{{ $comment->created_at->diffForHumans(locale: 'pl') }}</small>
            </div>
            @auth
                @if(Auth::id() === $comment->user_id || Auth::user()->is_admin)
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" wire:click="delete" class="btn btn-outline-danger btn-sm" onclick="confirm('Czy na pewno chcesz usunąć ten komentarz?') || event.stopImmediatePropagation()">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                @endif
            @endauth
        </div>
        <p class="card-text mb-0">{{ $comment->content }}</p>
    </div>
</div>
