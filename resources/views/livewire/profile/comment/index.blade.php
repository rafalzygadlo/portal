<div class="card border-0">
    <div class="card-body">
        <div class="d-flex justify-content-end mb-3">
            <select class="form-select form-select-sm w-auto" wire:model.live="type">
                <option value="all">All types</option>
                <option value="offer">Offer</option>
                <option value="article">Article</option>
                <option value="business">Business</option>
                <option value="todo">Todo</option>
                <option value="poll">Poll</option>
            </select>
        </div>

        @if($comments->count() > 0)
            <div class="list-group list-group-flush">
                @foreach($comments as $comment)
                    @php
                        $commentable = $comment->commentable;
                        $type = class_basename($comment->commentable_type);
                    @endphp

                    <div class="list-group-item px-0 py-3">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge bg-light text-dark border">{{ $type }}</span>
                                    <small class="text-muted">{{ $comment->created_at->format('d.m.Y H:i') }}</small>
                                </div>
                                <p class="mb-1">{{ Str::limit($comment->content, 240) }}</p>
                            </div>

                            @if($commentable)
                                <div>
                                    @if($comment->commentable_type === App\Models\Offer::class)
                                        <a href="{{ route('offer.show', $commentable) }}" class="btn btn-sm btn-outline-primary" target="_blank">Go to item</a>
                                    @elseif($comment->commentable_type === App\Models\Article::class)
                                        <a href="{{ route('article.show', $commentable) }}" class="btn btn-sm btn-outline-primary" target="_blank">Go to item</a>
                                    @elseif($comment->commentable_type === App\Models\Business::class)
                                        <a href="{{ route('business.show', $commentable) }}" class="btn btn-sm btn-outline-primary" target="_blank">Go to item</a>
                                    @elseif($comment->commentable_type === App\Models\Todo::class)
                                        <a href="{{ route('todo.show', $commentable) }}" class="btn btn-sm btn-outline-primary" target="_blank">Go to item</a>
                                    @elseif($comment->commentable_type === App\Models\Poll\Poll::class)
                                        <a href="{{ route('poll.show', $commentable) }}" class="btn btn-sm btn-outline-primary" target="_blank">Go to item</a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-3">
                {{ $comments->links() }}
            </div>
        @else
            <div class="alert alert-light text-center py-5">
                <i class="bi bi-chat-left-text text-muted" style="font-size: 2rem;"></i>
                <p class="mt-3 text-muted mb-0">You have not added any comments yet.</p>
            </div>
        @endif
    </div>
</div>
