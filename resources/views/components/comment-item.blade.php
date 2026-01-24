@props(['comment', 'isReply' => false])

<div class="card mb-3 border-0 @if($isReply) ms-5 @else bg-light @endif" @if($isReply) style="background-color: #f8f9fa;" @endif>
    <div class="card-body @if($isReply) py-2 @endif">
        <div class="d-flex justify-content-between align-items-center mb-1">
            <div class="fw-bold @if($isReply) small @endif">
                @if($isReply)
                    <i class="bi bi-arrow-return-right text-secondary me-1"></i>
                @else
                    <i class="bi bi-person-circle text-secondary"></i>
                @endif
                {{ $comment->user->first_name ?? 'Użytkownik' }}
            </div>
            <small class="text-muted" @if($isReply) style="font-size: 0.8rem;" @endif>{{ $comment->created_at->diffForHumans() }}</small>
        </div>
        <p class="mb-1 @if($isReply) small @endif">{{ $comment->content }}</p>

        <div class="d-flex gap-3 mt-2">
            @auth
                @if(!$isReply)
                    <button wire:click="$set('replyToId', {{ $comment->id }})" class="btn btn-link text-primary btn-sm p-0 text-decoration-none">
                        <i class="bi bi-reply"></i> Odpowiedz
                    </button>
                @endif
            @endauth

            @can('delete', $comment)
                <button wire:click="delete({{ $comment->id }})" class="btn btn-link text-danger btn-sm p-0 text-decoration-none @if($isReply) style="font-size: 0.8rem;" @endif" onclick="confirm('Czy na pewno usunąć?') || event.stopImmediatePropagation()">
                    Usuń
                </button>
            @endcan
        </div>
    </div>
</div>
