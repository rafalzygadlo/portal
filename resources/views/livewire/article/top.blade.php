<div class="col-lg-12 sticky-top">
    <h2 class="mt-2 border-bottom">Top 10</h2>
    <ul class="list-group list-group-flush">
        @forelse($topArticles as $index => $article)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center text-truncate">
                    <span class="badge bg-secondary rounded-pill me-2">{{ $index + 1 }}</span>
                    <a href="{{ route('article.show', $article) }}" class="fw-bold text-truncate text-decoration-none text-dark" style="max-width: 150px;">
                        {{ $article->title }}
                    </a>
                </div>
                <span class="badge bg-primary rounded-pill">{{ $article->getScore() }}</span>
            </li>
        @empty
            <li class="list-group-item text-muted text-center py-3">Brak artykułów w tym miesiącu.</li>
        @endforelse
    </ul>
</div>