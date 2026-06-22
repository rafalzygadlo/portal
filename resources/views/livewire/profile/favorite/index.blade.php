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

        @if($favorites->count() > 0)
            <div class="list-group list-group-flush">
                @foreach($favorites as $favorite)
                    @php
                        $item = $favorite->favoritable;
                        $type = class_basename($favorite->favoritable_type);
                    @endphp

                    <div class="list-group-item d-flex justify-content-between align-items-start px-0 py-3">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="badge bg-light text-dark border">{{ $type }}</span>
                                <small class="text-muted">{{ $favorite->created_at->format('d.m.Y H:i') }}</small>
                            </div>

                            @if($item)
                                <h6 class="mb-1 fw-bold">
                                    {{ $item->title ?? $item->name ?? $item->question ?? ('#' . $item->id) }}
                                </h6>
                            @else
                                <h6 class="mb-1 fw-bold text-muted">Removed item</h6>
                            @endif
                        </div>

                        @if($item)
                            <div>
                                @if($favorite->favoritable_type === App\Models\Offer::class)
                                    <a href="{{ route('offer.show', $item) }}" class="btn btn-sm btn-outline-primary" target="_blank">Open</a>
                                @elseif($favorite->favoritable_type === App\Models\Article::class)
                                    <a href="{{ route('article.show', $item) }}" class="btn btn-sm btn-outline-primary" target="_blank">Open</a>
                                @elseif($favorite->favoritable_type === App\Models\Business::class)
                                    <a href="{{ route('business.show', $item) }}" class="btn btn-sm btn-outline-primary" target="_blank">Open</a>
                                @elseif($favorite->favoritable_type === App\Models\Todo::class)
                                    <a href="{{ route('todo.show', $item) }}" class="btn btn-sm btn-outline-primary" target="_blank">Open</a>
                                @elseif($favorite->favoritable_type === App\Models\Poll\Poll::class)
                                    <a href="{{ route('poll.show', $item) }}" class="btn btn-sm btn-outline-primary" target="_blank">Open</a>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="mt-3">
                {{ $favorites->links() }}
            </div>
        @else
            <div class="alert alert-light text-center py-5">
                <i class="bi bi-heart text-muted" style="font-size: 2rem;"></i>
                <p class="mt-3 text-muted mb-0">You do not have any favorites yet.</p>
            </div>
        @endif
    </div>
</div>
