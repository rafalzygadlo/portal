
<div class="col-12 px-1 px-md-3">    <!-- NAGŁÓWEK STRONY & STATUS -->
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
    <livewire:breadcrumb route="offers.index" :category="$currentCategory" :key="'bc-'.$categorySlug" />


    <!-- GŁÓWNY UKŁAD: SIDEBAR + SIATKA -->
    <div class="row g-4">
        {{-- Nowoczesny Sidebar z Kategoriami --}}
        <div class="col-lg-12 col-xl-12 mb-4">
            <div class="card border-0 rounded-4 shadow-sm bg-white sticky-top">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-2 fw-bold text-dark d-flex align-items-center gap-2" style="font-size: 1.05rem;">
                    <i class="bi bi-folder2-open text-primary"></i> Categories
                </div>
                <div class="card-body px-4 pb-4 pt-2">
                    <livewire:category-bar route="offers.index" :categorySlug="$categorySlug" :currentCategory="$currentCategory" :key="'side-'.$categorySlug" />
                </div>
            </div>
        </div>

        {{-- Główna Treść (Siatka Ofert) --}}
        <div class="col-lg-12 col-xl-12">
            <div class="row g-4">
                @forelse ($offers as $offer)
                    <!-- KARTA OFERTY: Zmieniono z col-md-3 na col-md-6 col-xl-4 dla lepszej czytelności tekstu -->
                    <div class="col-12 col-sm-6 col-md-6 col-xl-3 fade-in-card" wire:key="offer-{{ $offer->id }}" >
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
                                    <a href="{{ route('offer.show', $offer) }}" class="text-decoration-none text-dark text-hover-primary stretched-link">
                                        {{ Str::limit($offer->title, 50) }}
                                    </a>
                                </h3>

                                <!-- Czas relatywny (np. 2 hours ago) -->
                                <div class="text-muted small mb-3 d-flex align-items-center gap-1" style="font-size: 0.8rem; opacity: 0.8;">
                                    <i class="bi bi-clock-history"></i> {{ $offer->created_at->diffForHumans() }}
                                </div>

                                <!-- Opis oferty -->
                                 {{--
                                <p class="card-text text-muted flex-grow-1 mb-4" style="font-size: 0.9rem; line-height: 1.5; opacity: 0.85;">
                                    {!! nl2br(strip_tags(Str::limit($offer->content, 110), '<a>')) !!}
                                </p>
                                --}}
                                
                                <div class="price">
                                    <strong>Cena: {{ number_format($offer->price, 2) }} PLN</strong>
                                </div>
                                
                                <!-- Przyciski Aktywności (Komentarze + Polubienia) -->
                                <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-auto" style="border-color: #f1f3f5 !important;">
                                    
                                    <!-- Data w pełnym formacie po lewej (Dyskretna) -->
                                    <div class="text-muted small d-flex align-items-center gap-1" style="font-size: 0.75rem;">
                                        <i class="bi bi-calendar3"></i> {{ $offer->created_at->translatedFormat('d M Y') }}
                                    </div>

                                    <livewire:favorite :model="$offer" :key="'favorite-offer-list-'.$offer->id" />
                                    
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
            @if($offers->hasMorePages())
            <div class="col-12 text-center p-4">
                <div wire:loading.remove wire:target="loadMore">
                    <button wire:click="loadMore" class="btn btn-outline-primary px-5 rounded-pill shadow-sm fw-bold">
                        Załaduj więcej
                    </button>
                </div>

                <div wire:loading wire:target="loadMore" class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Ładowanie...</span>
                    </div>
                    <div class="mt-2 text-secondary small">Ładowanie kolejnych aktywności...</div>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>