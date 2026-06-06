@props(['comment', 'isReply' => false])

@php
    $commenter = $comment->user->first_name ?? 'Użytkownik';
    $initial = \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($commenter, 0, 1));
@endphp

<div class="card mb-3 comment-card border-0 @if($isReply) comment-reply @endif">
    <div class="card-body py-3">
        <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold" style="width: 38px; height: 38px;">
                    {{ $initial }}
                </div>
                <div>
                    <div class="fw-semibold">{{ $commenter }}</div>
                    <div class="text-muted text-muted-small">{{ $comment->created_at->diffForHumans() }}</div>
                </div>
            </div>
            @can('delete', $comment)
                <button wire:click="delete({{ $comment->id }})" class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-trash"></i>
                </button>
            @endcan
        </div>

        <p class="mb-3 text-secondary">{{ $comment->content }}</p>

        <div class="d-flex flex-wrap gap-2">
            @auth
                @if(!$isReply)
                    <button wire:click="$set('replyToId', {{ $comment->id }})" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-reply me-1"></i> Odpowiedz
                    </button>
                @endif
            @endauth
        </div>
    </div>
</div>
