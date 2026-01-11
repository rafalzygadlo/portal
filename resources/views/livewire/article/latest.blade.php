<div class="col-lg-12">
    <h2 class="mb-4 pb-2 border-bottom">Najnowsze artykuły</h2>
    
    <div class="row">
    @forelse($latestArticles as $article)
        @php
            $colClass = 'col-12';
            if ($loop->iteration > 1 && $loop->iteration <= 5) {
                $colClass = 'col-md-6';
            } elseif ($loop->iteration > 5) {
                $colClass = 'col-md-4';
            }
            // Zmienne pomocnicze, aby uniknąć błędów, jeśli nie są zdefiniowane
            $userVote = $userVote ?? null;
            $isAuthor = $isAuthor ?? false;
        @endphp
        <div class="{{ $colClass }} mb-4">
            <div class="card border-0 h-100 shadow-sm">
                @if($article->image_path)
                    <a href="{{ route('article.show', $article) }}">
                        <img src="{{ asset('storage/' . $article->image_path) }}" class="card-img-top" alt="{{ $article->title }}" style="height: 200px; object-fit: cover;">
                    </a>
                @endif
                <div class="card-body d-flex flex-column">
                    <h3 class="card-title h5">
                        <span class="text-muted me-2">#{{ $article->rank ?? $loop->iteration }}</span>
                        <a href="{{ route('article.show', $article) }}" class="text-decoration-none text-dark">{{ $article->title }}</a>
                    </h3>
                    <p class="card-text text-muted small">
                            @if($article->user)
                        <i class="bi bi-person"></i><a href="{{ route('user.profile', $article->user) }}" class="text-decoration-none text-muted">{{ $article->user->first_name }}</a>
                    @else
                        Autor nieznany
                    @endif
                        <i class="bi bi-calendar"></i> {{ $article->created_at->format('d.m.Y H:i') }}
                    </p>
                    <p class="card-text flex-grow-1">{{ \Illuminate\Support\Str::limit($article->content, $loop->first ? 250 : 100) }}</p>
                    

                    <div class="d-flex  align-items-right mt-auto">
                        <i class="bi bi-hand-thumbs-up"> </i>{{ $article->upvotes->count() }}
                        <i class="bi bi-hand-thumbs-down"> </i>{{ $article->downvotes->count() }}
                        <button class="btn btn-sm btn-outline-secondary border-0 ms-2" onclick="if (navigator.share) { navigator.share({ title: {!! json_encode($article->title) !!}, url: '{{ route('article.show', $article) }}' }); } else { navigator.clipboard.writeText('{{ route('article.show', $article) }}'); alert('Link skopiowany do schowka!'); }">
                            <i class="bi bi-share"></i> Udostępnij
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5 text-muted bg-light rounded-3">
            <i class="bi bi-journal-text display-4"></i>
            <p class="mt-3 fs-5">Bądź pierwszy! Dodaj artykuł o Bolesławcu.</p>
        </div>
    @endforelse
    </div>
</div>