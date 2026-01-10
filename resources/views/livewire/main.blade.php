<div>
    <div class="container-fluid">
        <div class="mb-4">
            <div class="container-fluid py-3">
                <h3 class="fw-bold">Bolesławiec - Twórz z nami portal miasta!</h3>
                <p class="col-md-12 fs-10 mt-1">
                    Masz ciekawe informacje, zdjęcia lub opinię o wydarzeniach w Bolesławcu?
                    Napisz artykuł i podziel się nim z mieszkańcami.
                </p>
                
                <p class="col-md-12 fs-10">
                    <div>
                        <h4 class="alert-heading fw-bold mb-1">Wygraj 100 PLN!</h4>
                        <p class="mb-0">Co tydzień autor najlepszego artykułu otrzymuje nagrodę pieniężną.</p>
                    </div>
                </div>

                <div class="mt-4">
                    @auth
                        <a href="{{ route('article.create') }}" class="btn btn-primary btn-lg px-4 me-md-2">
                            <i class="bi bi-pencil-square"></i> Dodaj artykuł
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4 me-md-2">Zaloguj się, aby pisać</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg px-4">Zarejestruj się</a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-lg-9">
                <h2 class="mb-4 pb-2 border-bottom">Najnowsze artykuły</h2>
                
                @forelse($latestArticles as $article)
                    <div class="card mb-4 border-0">
                        @if($article->image_path)
                            <a href="{{ route('article.show', $article) }}">
                                <img src="{{ asset('storage/' . $article->image_path) }}" class="card-img-top" alt="{{ $article->title }}" style="height: 200px; object-fit: cover;">
                            </a>
                        @endif
                        <div class="card-body">
                            <h3 class="card-title">
                                <span class="text-muted me-2">#{{ $article->rank }}</span>
                                <a href="{{ route('article.show', $article) }}" class="text-decoration-none text-dark">{{ $article->title }}</a>
                            </h3>
                            <p class="card-text text-muted small">
                                <i class="bi bi-person"></i> {{ $article->user->first_name ?? 'Anonim' }} | 
                                <i class="bi bi-calendar"></i> {{ $article->created_at->format('d.m.Y H:i') }}
                            </p>
                            <p class="card-text">{{ \Illuminate\Support\Str::limit($article->content, 150) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <livewire:article.vote :article="$article" :key="'vote-'.$article->id" />
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 text-muted bg-light rounded-3">
                        <i class="bi bi-journal-text display-4"></i>
                        <p class="mt-3 fs-5">Bądź pierwszy! Dodaj artykuł o Bolesławcu.</p>
                    </div>
                @endforelse
            </div>

            <div class="col-lg-3">
                <div class="card border-0">
                    <div class="card-header">
                        <h4 class="mb-0 fs-5"><i class="bi bi-trophy"></i> Top 10</h4>
                    </div>
                    <ul class="list-group list-group-flush">
                        @forelse($topArticles as $index => $article)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center text-truncate">
                                    <span class="badge bg-secondary rounded-pill me-2">{{ $index + 1 }}</span>
                                    <a href="{{ route('article.show', $article) }}" class="fw-bold text-truncate text-decoration-none text-dark" style="max-width: 150px;">
                                        {{ $article->title }}
                                    </a>
                                </div>
                                <span class="badge bg-primary rounded-pill">{{ $article->votes_count }}</span>
                            </li>
                        @empty
                            <li class="list-group-item text-muted text-center py-3">Brak artykułów w tym tygodniu.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
