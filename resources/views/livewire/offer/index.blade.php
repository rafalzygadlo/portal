<div class="col-12 px-1 px-md-3">
    <!-- NAGŁÓWEK STRONY & STATUS -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-black fs-2 tracking-tight text-dark mb-1">Offers</h2>
            <p class="text-muted small mb-0 d-none d-sm-block">Find the best deals tailored just for you.</p>
        </div>
    </div>

    @if (session('status'))
        <div class="alert alert-success border-0 rounded-4 shadow-sm p-3 mb-4 d-flex align-items-center gap-2" style="background-color: #e6f4ea; color: #137333;">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <div class="fw-medium small">{{ session('status') }}</div>
        </div>
    @endif

    <!-- NOWOCZESNY, MINIMALISTYCZNY BREADCRUMB (Bez tła, czysta przestrzeń) -->
    <nav aria-label="breadcrumb" class="mb-5">
        <ol class="breadcrumb bg-transparent p-0 m-0 align-items-center" style="font-size: 0.9rem;">
            @if (!empty($breadcrumb))
                <li class="breadcrumb-item">
                    <a href="{{ route('offers.index') }}" class="text-decoration-none text-muted text-hover-primary fw-medium">All Offers</a>
                </li>
                @foreach ($breadcrumb as $crumb)
                    <li class="breadcrumb-item {{ $loop->last ? 'active text-dark fw-bold' : '' }}" @if($loop->last) aria-current="page" @endif>
                        @if (!$loop->last)
                            <a href="{{ route('offers.index', $crumb->slug) }}" class="text-decoration-none text-muted text-hover-primary fw-medium">{{ $crumb->name }}</a>
                        @else
                            {{ $crumb->name }}
                        @endif
                    </li>
                @endforeach
            @elseif (request()->has('category'))
                <li class="breadcrumb-item">
                    <a href="{{ route('offers.index') }}" class="text-decoration-none text-muted text-hover-primary fw-medium">All Offers</a>
                </li>
                <li class="breadcrumb-item active text-danger fw-semibold" aria-current="page">Category Not Found</li>
            @else
                <li class="breadcrumb-item active text-dark fw-bold" aria-current="page">
                    <i class="bi bi-grid-fill me-1.5 small opacity-75"></i> All Offers
                </li>
            @endif
        </ol>
    </nav>

    <!-- GŁÓWNY UKŁAD: SIDEBAR + SIATKA -->
    <div class="row g-4">
        {{-- Nowoczesny Sidebar z Kategoriami --}}
        <aside class="col-lg-3 col-xl-2 mb-4">
            <div class="card border-0 rounded-4 shadow-sm bg-white sticky-top" style="top: 20px; border: 1px solid #f1f3f5 !important;">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-2 fw-bold text-dark d-flex align-items-center gap-2" style="font-size: 1.05rem;">
                    <i class="bi bi-folder2-open text-primary"></i> Categories
                </div>
                <div class="card-body px-4 pb-4 pt-2">
                    <ul class="list-unstyled mb-0 d-flex flex-column gap-1">
                        @include('livewire.offer.category', ['categories' => $categories])
                    </ul>
                </div>
            </div>
        </aside>

        {{-- Główna Treść (Siatka Ofert) --}}
        <div class="col-lg-9 col-xl-10">
            <div class="row g-4">
                @forelse ($offers as $offer)
                    <!-- KARTA OFERTY: Zmieniono z col-md-3 na col-md-6 col-xl-4 dla lepszej czytelności tekstu -->
                    <div class="col-12 col-sm-6 col-md-6 col-xl-4 fade-in-card" wire:key="offer-{{ $offer->id }}" style="animation-delay: {{ $loop->index * 0.05 }}s">
                        <div class="card h-100 border-0 rounded-4 bg-white shadow-sm transition-hover d-flex flex-column overflow-hidden">
                            
                            <!-- Zdjęcie Oferty -->
                            <div class="position-relative overflow-hidden flex-shrink-0">
                                @if($offer->images->isNotEmpty())
                                    <img loading="lazy" src="{{ asset('storage/' . $offer->images->first()->path) }}" class="w-100 img-fluid transition-zoom" alt="{{ $offer->title }}" style="height: 200px; object-fit: cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                        <i class="bi bi-tag text-muted opacity-40" style="font-size: 2.5rem;"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Treść Karty -->
                            <div class="card-body p-4 d-flex flex-column flex-grow-1">
                                
                                <!-- Kategorie (Tagi) przeniesione na górę treści – ułatwia skanowanie wzrokiem -->
                                @if($offer->categories->isNotEmpty())
                                    <div class="mb-2 d-flex flex-wrap gap-1">
                                        @foreach($offer->categories as $category)
                                            <a href="{{ route('offers.index', $category->slug) }}" class="text-decoration-none">
                                                <span class="badge bg-primary-subtle text-primary border-0 rounded-pill px-2.5 py-1" style="font-size: 0.75rem; fw-semibold;">{{ $category->name }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Tytuł oferty -->
                                <h3 class="fs-5 fw-bold mb-2 tracking-tight">
                                    <a href="{{ route('offers.show', $offer) }}" class="text-decoration-none text-dark text-hover-primary">
                                        {{ Str::limit($offer->title, 50) }}
                                    </a>
                                </h3>

                                <!-- Czas relatywny (np. 2 hours ago) -->
                                <div class="text-muted small mb-3 d-flex align-items-center gap-1" style="font-size: 0.8rem; opacity: 0.8;">
                                    <i class="bi bi-clock-history"></i> {{ $offer->created_at->diffForHumans() }}
                                </div>

                                <!-- Opis oferty -->
                                <p class="card-text text-muted flex-grow-1 mb-4" style="font-size: 0.9rem; line-height: 1.5; opacity: 0.85;">
                                    {!! nl2br(strip_tags(Str::limit($offer->content, 110), '<a>')) !!}
                                </p>

                                <!-- Przyciski Aktywności (Komentarze + Polubienia) -->
                                <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-auto" style="border-color: #f1f3f5 !important;">
                                    
                                    <!-- Data w pełnym formacie po lewej (Dyskretna) -->
                                    <div class="text-muted small d-flex align-items-center gap-1" style="font-size: 0.75rem;">
                                        <i class="bi bi-calendar3"></i> {{ $offer->created_at->translatedFormat('d M Y') }}
                                    </div>

                                    <!-- Minimalistyczna grupa przycisków typu "Pill" -->
                                    <div class="bg-light p-1 rounded-pill d-flex align-items-center gap-1 border" style="border-color: #e9ecef !important;">
                                        <!-- Komentarze -->
                                        <button class="btn btn-sm btn-transparent rounded-pill border-0 px-2.5 py-1 text-dark small d-flex align-items-center gap-1 transition-all"
                                                wire:click="$emit('openComments', {{ $offer->id }})" style="font-size: 0.8rem;">
                                            <i class="bi bi-chat-text text-secondary"></i>
                                            <span class="fw-semibold">{{ $offer->comments_count ?? $offer->comments()->count() }}</span>
                                        </button>

                                        <!-- Polubienia -->
                                        <button class="btn btn-sm rounded-pill border-0 px-2.5 py-1 d-flex align-items-center gap-1 transition-all {{ $offer->likes()->where('user_id', auth()->id())->exists() ? 'bg-danger text-white shadow-sm' : 'btn-transparent text-dark' }}"
                                                wire:click="$emit('toggleLike', {{ $offer->id }})" style="font-size: 0.8rem;">
                                            <i class="bi {{ $offer->likes()->where('user_id', auth()->id())->exists() ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                                            <span class="fw-semibold">{{ $offer->likes_count ?? $offer->likes()->count() }}</span>
                                        </button>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5 bg-light rounded-4 border border-dashed">
                            <i class="bi bi-inbox display-4 d-block mb-3 text-muted" style="opacity: 0.6;"></i>
                            <h5 class="fw-semibold text-dark">No offers available</h5>
                            <p class="text-muted small mb-0">Check back later or browse other categories.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Paginacja -->
            <div class="d-flex justify-content-center mt-5">
                {{ $offers->links() }}
            </div>
        </div>
    </div>
</div>