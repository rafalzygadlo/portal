
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
    <livewire:breadcrumb selectEvent="offer-category-selected" :category="$currentCategory" :key="'bc-'.$categorySlug" />

    <div class="row g-4 align-items-start">
        <aside class="col-12">
            <details class="border-bottom border-top py-3" open>
                <summary class="d-flex align-items-center justify-content-between gap-2 fw-bold text-dark" style="cursor: pointer; list-style: none;">
                    <span class="d-flex align-items-center gap-2">
                        <i class="bi bi-folder2-open text-primary"></i>
                        Filtr kategorii
                    </span>
                    <span class="d-flex align-items-center gap-2 text-muted small">
                        <span class="d-none d-sm-inline">Zwiń</span>
                        <i class="bi bi-chevron-down"></i>
                    </span>
                </summary>
                <div class="pt-3">
                    <livewire:category-bar orientation="horizontal" selectEvent="offer-category-selected" :currentCategory="$currentCategory" :key="'side-'.$categorySlug" />
                </div>
            </details>
        </aside>

        {{-- Główna treść ofert w układzie listy --}}
        <div class="col-12">
            <div class="border-top">
                @forelse ($offers as $offer)
                    <article class="d-flex align-items-center gap-3 gap-md-4 py-3 border-bottom" wire:key="offer-{{ $offer->id }}">
                        <a href="{{ route('offer.show', $offer) }}" class="d-flex align-items-center justify-content-center flex-shrink-0 bg-light overflow-hidden" style="width: 5.5rem; height: 4.5rem;">
                            @if($offer->images->isNotEmpty())
                                <img loading="lazy" src="{{ asset('storage/' . $offer->images->first()->path) }}" class="w-100 h-100 object-fit-cover" alt="{{ $offer->title }}">
                            @else
                                <i class="bi bi-tag text-muted fs-3"></i>
                            @endif
                        </a>

                        <div class="flex-grow-1 min-width-0">
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                @if($offer->isPromoted())
                                    <span class="badge bg-warning-subtle text-warning border border-warning"><i class="bi bi-star-fill me-1"></i> Promowane</span>
                                @endif
                                @if($offer->categories->isNotEmpty())
                                    <span class="text-muted small">{{ $offer->categories->pluck('name')->join(', ') }}</span>
                                @endif
                            </div>
                            <h3 class="h6 fw-bold mb-1 text-truncate">
                                <a href="{{ route('offer.show', $offer) }}" class="text-decoration-none text-dark hover-primary">{{ Str::limit($offer->title, 70) }}</a>
                            </h3>
                            <div class="d-flex flex-wrap align-items-center gap-2 text-muted small">
                                <strong class="text-dark">{{ number_format($offer->price, 2) }} PLN</strong>
                                <span aria-hidden="true">·</span>
                                <time datetime="{{ $offer->created_at->toIso8601String() }}">{{ $offer->created_at->diffForHumans() }}</time>
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-2 flex-shrink-0">
                            <livewire:promote :model="$offer" :key="'promote-offer-'.$offer->id" />
                            <livewire:favorite :model="$offer" :key="'favorite-offer-list-'.$offer->id" />
                            <a href="{{ route('offer.show', $offer) }}" class="btn btn-sm btn-light rounded-circle d-none d-sm-inline-flex align-items-center justify-content-center" aria-label="Otwórz ofertę">
                                <i class="bi bi-arrow-right" aria-hidden="true"></i>
                            </a>
                        </div>
                    </article>
                @empty
                    <div class="text-center py-5 border-bottom">
                        <i class="bi bi-inbox display-6 d-block mb-3 text-muted"></i>
                        <h5 class="fw-semibold text-dark">No offers available</h5>
                        <p class="text-muted small mb-0">Check back later or browse other categories.</p>
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