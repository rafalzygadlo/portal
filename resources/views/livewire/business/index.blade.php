<div class="col-12 px-md-3" x-data="{ view: 'grid' }">
    
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-stretch align-items-sm-center mb-3">
        <h2 class="mb-1 fw-black text-center">Business</h2>
        
        <div class="btn-group btn-group-sm">
            <button class="btn" :class="view === 'grid' ? 'btn-primary' : 'btn-outline-primary'" @click="view = 'grid'">
                <i class="bi bi-grid-3x3-gap"></i> Kafelki
            </button>
            <button class="btn" :class="view === 'list' ? 'btn-primary' : 'btn-outline-primary'" @click="view = 'list'">
                <i class="bi bi-list-ul"></i> Lista
            </button>
        </div>
    </div>

    @if (session('status'))
        <div class="alert alert-success rounded-1 small py-2">
            {{ session('status') }}
        </div>
    @endif

    <!-- NOWOCZESNY, MINIMALISTYCZNY BREADCRUMB (Bez tła, czysta przestrzeń) -->
    <livewire:breadcrumb selectEvent="business-category-selected" :category="$currentCategory" :key="'bc-'.$categorySlug" />

    <!-- Pole wyszukiwania -->
    <div class="my-4">
        <div class="input-group input-group-lg">
            <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
            <input wire:model.live.debounce.300ms="search" type="text" class="form-control bg-light border-0" placeholder="Szukaj firmy ...">
        </div>
    </div>


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
                    <livewire:category-bar orientation="horizontal" selectEvent="business-category-selected" :currentCategory="$currentCategory" :key="'side-'.$categorySlug" />
                </div>
            </details>
        </aside>

    <div class="row g-3 g-md-4">
        @forelse ($businesses as $business)
            <div :class="view === 'grid' ? 'col-12 col-md-6 col-xl-4' : 'col-12'">
                <div class="card h-100 shadow-sm border-0 rounded-3 transition-hover">
                    <div class="card-body d-flex flex-column p-3 p-md-4">
                    @if($business->is_claimed)
                        <i class="bi bi-patch-check-fill text-primary position-absolute" style="top: 0.5rem; right: 0.5rem; font-size: 1.25rem;" title="Verified"></i>
                    @else
                        <i class="bi bi-patch-exclamation-fill text-secondary position-absolute" style="top: 0.5rem; right: 0.5rem; font-size: 1.25rem;" title="Not Verified"></i>
                    @endif

                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="fw-bold mb-0 me-3">
                                <a href="{{ route('business.show', $business) }}" class="text-dark text-decoration-none stretched-link">{{ $business->name }}</a>
                            </h5>
                        </div>

                        <p class="text-muted small mb-3">{{ Str::limit($business->description, 100) }}</p>

                        @if($business->categories->isNotEmpty())
                            <div class="mb-3 d-flex flex-wrap gap-1">
                                @foreach($business->categories as $category)
                                    <span class="badge bg-primary bg-opacity-10 text-primary-emphasis fw-normal" style="font-size: 0.7rem;">
                                        {{ $category->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif

                        <div class="mt-auto d-flex justify-content-between align-items-center pt-2 border-top">
                            <div class="small text-muted">
                                <i class="bi bi-clock-history"></i> {{ $business->created_at->diffForHumans() }}
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                @if($business->subdomain)
                                    <a style="position: relative; z-index: 1;" href="https://{{ $business->subdomain }}.{{ env('DOMAIN_NAME') }}" target="_blank" class="btn btn-sm btn-light border-0" title="{{ $business->subdomain }}.{{ env('DOMAIN_NAME') }}"><i class="bi bi-globe"></i></a>
                                @endif
                                <livewire:favorite :model="$business" :key="'favorite-business-list-'.$business->id" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-secondary rounded-1 text-center py-4">
                    <i class="bi bi-building-x d-block fs-2 mb-2 text-muted"></i>
                    Brak firm w tej kategorii.
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-4 d-flex justify-content-center justify-content-md-start">
        {{ $businesses->links() }}
    </div>
</div>