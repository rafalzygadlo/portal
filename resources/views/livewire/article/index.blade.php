<div class="col-12 px-1 px-md-3">
    <!-- NOWOCZESNY NAGŁÓWEK: Minimalistyczny, z subtelnym przełącznikiem (pill-switch) -->
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-stretch align-items-sm-center gap-3 mb-5">
        <div>
            <h2 class="mb-1 fw-black fs-2 tracking-tight text-dark">{{ $sort === 'popular' ? 'Trending' : 'Fresh' }} Stories</h2>
            <p class="text-muted small mb-0 d-none d-sm-block">Explore the best content from our community.</p>
        </div>
        <div class="bg-light p-1 rounded-pill d-flex align-items-center justify-content-between" style="border: 1px solid #e9ecef;">
            <button wire:click="setSort('newest')" class="btn rounded-pill btn-sm px-3 py-2 fw-medium transition-all {{ $sort === 'newest' ? 'btn-white shadow-sm text-dark' : 'text-secondary bg-transparent border-0' }}" style="font-size: 0.85rem;">Newest</button>
            <button wire:click="setSort('popular')" class="btn rounded-pill btn-sm px-3 py-2 fw-medium transition-all {{ $sort === 'popular' ? 'btn-white shadow-sm text-dark' : 'text-secondary bg-transparent border-0' }}" style="font-size: 0.85rem;">Popular</button>
        </div>
    </div>

    <!-- SIATKA ARTYKUŁÓW -->
    <div class="row g-4">
        @forelse($latestArticles as $article)
            @php
                $label = match (true) {
                    $article->created_at->isToday() => 'Today',
                    $article->created_at->isYesterday() => 'Yesterday',
                    default => $article->created_at->translatedFormat('d M Y'),
                };

                // Nowoczesny, dynamiczny podział siatki
                if ($loop->first) {
                    $colClass = 'col-12 col-lg-8'; // Główny artykuł dnia
                } elseif ($loop->iteration > 1 && $loop->iteration <= 3) {
                    $colClass = 'col-12 col-md-6 col-lg-4'; // Dwa mniejsze artykuły obok głównego
                } else {
                    $colClass = 'col-12 col-sm-6 col-md-4'; // Pozostałe w równej siatce
                }
            @endphp

            <div class="{{ $colClass }}" wire:key="article-{{ $article->id }}">
                <div class="card h-100 bg-white border-0 rounded-4 overflow-hidden transition-hover shadow-sm d-flex flex-column position-relative">
                    
                    <!-- Zdjęcie + Nowoczesny, lewitujący wskaźnik daty (zamiast brzydkiego separatora) -->
                    <div class="position-relative flex-shrink-0 overflow-hidden group">
                        @if($article->images->isNotEmpty())
                            <img loading="lazy" src="{{ asset('storage/' . $article->images->first()->path) }}" class="w-100 img-fluid transition-zoom" alt="{{ $article->title }}" style="height: {{ $loop->first ? '280px' : '180px' }}; object-fit: cover;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: {{ $loop->first ? '280px' : '180px' }};">
                                <i class="bi bi-file-earmark-post text-muted opacity-50" style="font-size: 2.5rem;"></i>
                            </div>
                        @endif

                        <!-- Szklany Badge z Datą lewitujący nad zdjęciem -->
                        <div class="position-absolute top-0 start-0 m-3 px-2.5 py-1.5 rounded-3 glass-badge small fw-semibold text-dark">
                            <i class="bi bi-lightning-charge-fill text-warning me-1 small"></i>{{ $label }}
                        </div>
                    </div>

                    <!-- Treść karty -->
                    <div class="card-body p-4 d-flex flex-column flex-grow-1">
                        
                        <!-- Tytuł z dynamicznym rozmiarem fontu -->
                        <h3 class="{{ $loop->first ? 'fs-4' : 'fs-5' }} fw-bold mb-2 tracking-tight">
                            <a href="{{ route('articles.show', $article) }}" class="text-decoration-none text-dark text-hover-primary">
                                {{ $article->title }}
                            </a>
                        </h3>

                        <!-- Skrócony Opis -->
                        <p class="card-text text-muted flex-grow-1 mb-4" style="font-size: 0.925rem; line-height: 1.6; opacity: 0.85;">
                            {{ \Illuminate\Support\Str::limit($article->content, $loop->first ? 180 : 95) }}
                        </p>

                        <!-- Nowoczesny, czysty pasek autora (Avatar-style + statystyki) -->
                        <div class="d-flex align-items-center justify-content-between mt-auto pt-3 border-top" style="border-color: #f1f3f5 !important;">
                            <div class="d-flex align-items-center gap-2 text-truncate me-2">
                                <!-- Atrapa avatara z pierwszej litery imienia -->
                                <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center fw-bold text-uppercase" style="width: 32px; height: 32px; font-size: 0.8rem; flex-shrink: 0;">
                                    {{ substr($article->user->first_name ?? 'U', 0, 1) }}
                                </div>
                                <div class="text-truncate lh-sm">
                                    <span class="d-block text-dark fw-semibold small">
                                        @if($article->user)
                                            <a href="{{ route('user.profile', $article->user) }}" class="text-decoration-none text-dark">{{ $article->user->first_name }}</a>
                                        @else
                                            Anonym
                                        @endif
                                    </span>
                                    <small class="text-muted" style="font-size: 0.75rem;">{{ $article->created_at->format('H:i') }}</small>
                                </div>
                            </div>

                            <!-- Statystyki i Głosowanie spięte w nowoczesny blok -->
                             {{-- 
                            <div class="d-flex align-items-center gap-3">
                                <div class="small text-muted" title="Comments">
                                    <i class="bi bi-chat-square-text me-1"></i>{{ $article->comments_count ?? $article->comments()->count() }}
                                </div>
                                <div class="bg-light rounded-pill px-2 py-0.5 d-flex align-items-center gap-1 border">
                                    <livewire:article.vote :model="$article" :key="'vote-'.$article->id" />
                                </div>
                            </div>
                            --}}
                        </div>
                        

                    </div>

                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5 bg-light rounded-4 border border-dashed">
                <i class="bi bi-journal-text display-4 d-block mb-3 text-muted"></i>
                <h5 class="fw-semibold">No articles found</h5>
                <p class="text-muted small">Be the pioneer and write something inspiring!</p>
            </div>
        @endforelse
    </div>

    <!-- Nowoczesny przycisk "Load more" oparty na czystej geometrii -->
    @if($latestArticles->isNotEmpty())
        <div class="d-flex justify-content-center mt-5"> 
            <button class="btn btn-dark rounded-pill px-5 py-2.5 fw-semibold shadow-sm w-100 w-sm-auto d-flex align-items-center justify-content-center gap-2" wire:click.prevent="more" wire:loading.attr="disabled">
                <span wire:loading class="spinner-border spinner-border-sm" role="status"></span>
                <span wire:loading.remove><i class="bi bi-plus-lg"></i> Load More Stories</span>
            </button>
        </div>
    @endif
</div>