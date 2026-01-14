<div class="col">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <h2 class="mb-0">{{ $sort === 'popular' ? 'Najpopularniejsze' : 'Najnowsze' }} artykuły</h2>
        <div class="btn-group" role="group">

            <button wire:click="setSort('newest')" class="btn btn-sm {{ $sort === 'newest' ? 'btn-primary' : 'btn-outline-primary' }}">Najnowsze</button>
            <button wire:click="setSort('popular')" class="btn btn-sm {{ $sort === 'popular' ? 'btn-primary' : 'btn-outline-primary' }}">Popularne</button>
        </div>
    </div>

    <div class="row">
        @php
        $currentDay = null;
        @endphp
        @forelse($latestArticles as $article)
        @php
        $colClass = 'col-12';
        if ($loop->iteration > 1 && $loop->iteration <= 5)
            {
            $colClass='col-md-6' ;
            } elseif ($loop->iteration > 5) {
            $colClass = 'col-md-4';
            }
            $userVote = $userVote ?? null;

            $articleDay = $article->created_at->format('Y-m-d');
            
            $label = match (true) {
                $article->created_at->isToday() => 'Dziś',
                $article->created_at->isYesterday() => 'Wczoraj',
                default => $article->created_at->translatedFormat('l, d F Y'),
            };
            @endphp

            @if ($articleDay !== $currentDay)
            <div class="d-flex align-items-center my-4">
                <div class="flex-grow-1 border-top"></div>
                    <div class="badge rounded-pill bg-primary px-3 fw-semibold">
                        {{ $label }}
                    </div>
                <div class="flex-grow-1 border-top"></div>
            </div>

            @php
            $currentDay = $articleDay;
            @endphp
            @endif


            <div class="{{ $colClass }} mb-4" wire:key="'article-'.$article->id">
                <div class="card h-100 border-1 shadow-sm overflow-hidden">                    
                    <div class="d-flex h-100">
                        <div class="card-body d-flex flex-column">
                            <h3 class="card-title">
                                <span class="badge rounded-pill bg-primary">{{ $article->votes_sum_value  }}</span>
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

                                <span class="badge rounded-pill bg-primary"><i class="bi bi-chat"></i> {{ $article->comments()->count() }}</span>
                                id:<span class="badge rounded-pill bg-secondary">{{ $article->id  }}</span>
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
            </div>
            @empty
            <div class="col-12 text-center py-5 text-muted bg-light rounded-3">
                <i class="bi bi-journal-text display-4"></i>
                <p class="mt-3 fs-5">Bądź pierwszy! Dodaj artykuł o Bolesławcu.</p>
            </div>
            @endforelse

    </div>
    
    <div class="d-flex justify-content-center mt-3">   
        <a class="btn btn-primary" wire:click.prevent="more">Pokaż więcej artykułów</a>
    </div>
</div>