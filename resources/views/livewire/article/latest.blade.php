<div class="col">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <h2 class="mb-0">{{ $sort === 'popular' ? 'Most popular' : 'Newest' }} articles</h2>
        <div class="btn-group" role="group">

            <button wire:click="setSort('newest')" class="btn btn-sm {{ $sort === 'newest' ? 'btn-primary' : 'btn-outline-primary' }}">Newest</button>
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
                $article->created_at->isToday() => 'Today',
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
                <div class="article-card h-100 overflow-hidden">
                    <div class="card-body d-flex flex-column p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3 gap-3">
                            <span class="badge-soft">{{ $article->votes_sum_value }} głosów</span>
                            <span class="badge bg-primary bg-opacity-10 text-primary">{{ $article->comments()->count() }} komentarzy</span>
                        </div>

                        <h3 class="card-title fs-5 fw-semibold mb-3">
                            <a href="{{ route('articles.show', $article) }}">{{ $article->title }}</a>
                        </h3>

                        <div class="d-flex flex-wrap align-items-center text-muted article-meta mb-3 small">
                            <div class="d-flex align-items-center gap-1">
                                <i class="bi bi-person-fill"></i>
                                @if($article->user)
                                    <a href="{{ route('user.profile', $article->user) }}" class="text-decoration-none text-muted">{{ $article->user->first_name }}</a>
                                @else
                                    Autor nieznany
                                @endif
                            </div>
                            <div class="d-flex align-items-center gap-1">
                                <i class="bi bi-calendar-event"></i>
                                {{ $article->created_at->format('d.m.Y H:i') }}
                            </div>
                            <div class="d-flex align-items-center gap-1">
                                <i class="bi bi-hash"></i>
                                {{ $article->id }}
                            </div>
                            @if(Auth::check() && $article->user_id === Auth::id())
                                <span class="badge bg-primary bg-opacity-15 text-primary">Your article</span>
                            @endif
                        </div>

                        <p class="card-text text-muted flex-grow-1 mb-4">{{ \Illuminate\Support\Str::limit($article->content, $loop->first ? 250 : 100) }}</p>

                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <div class="text-muted small">Czytaj więcej...</div>
                            <livewire:article.vote :model="$article" :key="'vote-single-'.$article->id" />
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5 text-muted bg-light rounded-3">
                <i class="bi bi-journal-text display-4"></i>
                <p class="mt-3 fs-5">Be first! Add an article about Boleslawiec.</p>
            </div>
            @endforelse

    </div>
    
    <div class="d-flex justify-content-center mt-3">   
        <a class="btn btn-primary" wire:click.prevent="more">Show more articles</a>
    </div>
</div>