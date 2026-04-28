<div class="col">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <h2 class="mb-0">{{ $sort === 'popular' ? 'Most popular' : 'Newest' }} articles</h2>
        <div class="btn-group" role="group">
            <button wire:click="setSort('newest')" class="btn btn-sm {{ $sort === 'newest' ? 'btn-primary' : 'btn-outline-primary' }}">Newest</button>
            <button wire:click="setSort('popular')" class="btn btn-sm {{ $sort === 'popular' ? 'btn-primary' : 'btn-outline-primary' }}">Popular</button>
        </div>
    </div>

    <div class="row">
        @php $currentDay = null; @endphp

        @forelse($latestArticles as $article)
            @php
                // Date Label Logic
                $articleDay = $article->created_at->format('Y-m-d');
                $label = match (true) {
                    $article->created_at->isToday() => 'Today',
                    $article->created_at->isYesterday() => 'Yesterday',
                    default => $article->created_at->translatedFormat('l, d F Y'),
                };

                // Grid Logic
                $colClass = 'col-12';
                if ($loop->iteration > 1 && $loop->iteration <= 5) {
                    $colClass = 'col-md-6';
                } elseif ($loop->iteration > 5) {
                    $colClass = 'col-md-4';
                }
            @endphp

            {{-- Date Separator --}}
            @if ($articleDay !== $currentDay)
                <div class="col-12">
                    <div class="d-flex align-items-center my-4">
                        <div class="flex-grow-1 border-top"></div>
                        <div class="badge rounded-pill bg-primary px-3 fw-semibold mx-2">
                            {{ $label }}
                        </div>
                        <div class="flex-grow-1 border-top"></div>
                    </div>
                </div>
                @php $currentDay = $articleDay; @endphp
            @endif

            {{-- Article Card --}}
            <div class="{{ $colClass }} mb-4" wire:key="article-{{ $article->id }}">
                <div class="card h-100 border-1 shadow-sm overflow-hidden">
                    <div class="card-body d-flex flex-column">
                        <h3 class="card-title h5">
                            <span class="badge rounded-pill bg-primary me-1">{{ $article->votes_sum_value ?? 0 }}</span>
                            <a href="{{ route('articles.show', $article) }}" class="text-decoration-none text-dark">{{ $article->title }}</a>
                        </h3>

                        <div class="card-text text-muted small mb-2">
                            <i class="bi bi-person"></i>
                            @if($article->user)
                                <a href="{{ route('user.profile', $article->user) }}" class="text-decoration-none text-muted">{{ $article->user->first_name }}</a>
                            @else
                                Unknown Author
                            @endif
                            
                            <span class="ms-2"><i class="bi bi-calendar"></i> {{ $article->created_at->format('d.m.Y H:i') }}</span>

                            @if(Auth::check() && $article->user_id === Auth::id())
                                <span class="badge rounded-pill bg-info text-dark ms-2"><i class="bi bi-pencil"></i> Yours</span>
                            @endif
                        </div>

                        <p class="card-text flex-grow-1">
                            {{ \Illuminate\Support\Str::limit($article->content, $loop->first ? 250 : 100) }}
                        </p>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="small">
                                <i class="bi bi-chat"></i> {{ $article->comments_count ?? $article->comments()->count() }}
                            </div>
                            <livewire:article.vote :model="$article" :key="'vote-'.$article->id" />
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5 text-muted bg-light rounded-3">
                <i class="bi bi-journal-text display-4"></i>
                <p class="mt-3 fs-5">Be the first to add an article!</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination/Load More --}}
    @if($latestArticles->isNotEmpty())
        <div class="d-flex justify-content-center mt-3">   
            <button class="btn btn-primary" wire:click.prevent="more" wire:loading.attr="disabled">
                <span wire:loading class="spinner-border spinner-border-sm me-1"></span>
                Show more articles
            </button>
        </div>
    @endif
</div>