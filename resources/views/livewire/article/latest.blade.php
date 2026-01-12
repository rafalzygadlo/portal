<div class="col">
    <h2 class="mb-4 pb-0 border-bottom">Najnowsze artykuły</h2>

    <div class="row">
        @forelse($latestArticles as $article)
        @php
        $colClass = 'col-12';
        if ($loop->iteration > 1 && $loop->iteration <= 5) {
            $colClass='col-md-6' ;
            } elseif ($loop->iteration > 5) {
            $colClass = 'col-md-4';
            }
            // Zmienne pomocnicze, aby uniknąć błędów, jeśli nie są zdefiniowane
            $userVote = $userVote ?? null;
            $isAuthor = $isAuthor ?? false;
            @endphp
            <div class="{{ $colClass }} mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <a href="{{ route('article.show', $article) }}">
                        @if($article->image_path)
                        <img src="{{ asset('storage/' . $article->image_path) }}" class="card-img-top" alt="{{ $article->title }}" style="height: 200px; object-fit: cover;">
                        @else
                        <img src="https://placehold.co/600x400/{{ substr(md5($article->title), 0, 6) }}/FFF?text={{ urlencode(\Illuminate\Support\Str::limit($article->title, 30)) }}" class="card-img-top" alt="{{ $article->title }}" style="height: 200px; object-fit: cover;">
                        @endif
                    </a>
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
                        @if(Auth::check() && $article->user_id === Auth::id())
                        <i class="bi bi-pencil ms-2"></i>
                        <span class="badge rounded-pill bg-primary">Twój artykuł</span>
                        @endif
                    </p>
                    <p class="card-text flex-grow-1">{{ \Illuminate\Support\Str::limit($article->content, $loop->first ? 250 : 100) }}</p>
                
                <div class="mt-auto">
                    <div>
                        <livewire:article.vote :model="$article" :key="'vote-single-'.$article->id" />
                    </div>
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