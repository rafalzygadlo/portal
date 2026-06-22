<div class="card border-0">
    <div class="card-body">
        @if($articles->count() > 0)
            <div class="list-group list-group-flush">
                @foreach($articles as $article)
                    <div class="list-group-item d-flex justify-content-between align-items-start px-0 py-3">
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-bold">{{ $article->title }}</h6>
                            <p class="text-muted small mb-2">{{ Str::limit($article->content, 100) }}</p>
                            <small class="text-muted">Created: {{ $article->created_at->format('d.m.Y H:i') }}</small>
                        </div>
                        <div class="d-flex gap-2 ms-2">
                            <a href="{{ route('article.show', $article) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                <i class="bi bi-eye"></i> View
                            </a>
                            <a href="{{ route('profile.article.edit', $article) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-light text-center py-5">
                <i class="bi bi-newspaper text-muted" style="font-size: 2rem;"></i>
                <p class="mt-3 text-muted">You haven't written any articles yet.</p>
                <a href="{{ route('profile.article.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg"></i> Write your first article
                </a>
            </div>
        @endif
    </div>
</div>
