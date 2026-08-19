@props(['comment', 'isReply' => false])

@php
    $commenter = $comment->user->first_name ?? 'Użytkownik';
    $initial = \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($commenter, 0, 1));
@endphp

<div class="card mb-2 comment-card border-0 @if($isReply) comment-reply ms-4 @endif">
    <div class="card-body py-2">
        <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold" style="width: 38px; height: 38px;">
                    {{ $initial }}
                </div>
                <div>
                    <div class="fw-semibold">{{ $commenter }}</div>
                    <div class="text-muted text-muted-small">{{ $comment->created_at->diffForHumans() }}</div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-1">
                @auth
                    @if(!$isReply)
                    <button wire:click="$set('replyToId', {{ $comment->id }})" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-reply me-1"></i> Odpowiedz
                    </button>
                    @endif
                @endauth
                @can('delete', $comment)
                    <button wire:click="delete({{ $comment->id }})" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-trash"></i>
                    </button>
                @endcan
            </div>
        </div>

        <p class="mb-2 text-secondary">{{ $comment->content }}</p>
    </div>
</div>
