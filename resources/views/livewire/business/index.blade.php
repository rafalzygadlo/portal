<div class="col-12 px-1 px-md-3">
    <!-- NAGŁÓWEK: Elastyczny na mobile, przycisk dopasowuje się do szerokości -->
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-stretch align-items-sm-center">
        <h2 class="mb-1 fw-black text-center ">Business</h2>
       {{--  
        <a href="{{ route('business.create') }}" class="btn btn-outline-dark btn-sm d-flex align-items-center justify-content-center py-2 py-sm-1">
            <i class="bi bi-plus-circle me-1"></i>Add your business
        </a>
        --}}
    </div>

    @if (session('status'))
        <div class="alert alert-success rounded-1 small py-2">
            {{ session('status') }}
        </div>
    @endif

    <!-- FILTRY: Na desktopie standardowy box, na smartfonie zgrabny, przewijany w bok pasek (overflow-x-auto) bez łamania linii -->
    <div class="mb-4 p-3 p-md-4 rounded-1 bg-light">
        <h5 class="fw-semibold mb-2 mb-md-3 small text-uppercase text-muted" style="letter-spacing: 0.5px;">Filtruj firmy</h5>
        <div class="d-flex flex-nowrap flex-md-wrap gap-2 overflow-x-auto pb-2 pb-md-0" style="scrollbar-width: none; -webkit-overflow-scrolling: touch;">
            @foreach($categories as $cat)
                <div class="flex-shrink-0">
                    <input type="checkbox" class="btn-check" value="{{ $cat->slug }}"
                           id="cat-{{ $cat->id }}" wire:model.live="selectedCategories" autocomplete="off">
                    <label class="btn btn-sm btn-outline-secondary text-nowrap" for="cat-{{ $cat->id }}">{{ $cat->name }}</label>
                </div>
            @endforeach
        </div>
    </div>

    <!-- GRID: Odpowiednio zestopniowany. col-12 na telefon, col-md-6 na tablet, col-xl-4 na duże monitory -->
    <div class="row g-3 g-md-4">
        @forelse ($businesses as $business)
            <div class="col-12 col-md-6 col-xl-4">
                <div class="h-100 p-3 p-md-4 d-flex flex-column bg-white rounded-1 border border-secondary-subtle shadow-sm">
                    
                    <!-- Góra karty: Tytuł i przycisk View -->
                    <div class="d-flex justify-content-between align-items-start mb-3 gap-2">
                        <div class="text-truncate me-2">
                            <h5 class="mb-1 fw-semibold text-break fs-6 fs-md-5">{{ $business->name }}</h5>
                            <div class="text-secondary" style="font-size: 0.75rem;">
                                <i class="bi bi-calendar3 me-1"></i>{{ $business->created_at->diffForHumans() }}
                            </div>
                        </div>
                        <a href="{{ route('business.show', $business) }}" class="btn btn-sm btn-outline-primary flex-shrink-0">View</a>
                    </div>

                    <!-- Opis firmy -->
                    <p class="text-secondary mb-3 small-md" style="font-size: 0.9rem; line-height: 1.4;">
                        {{ Str::limit($business->description, 120) }}
                    </p>

                    <!-- Kategorie przypisane do firmy -->
                    @if($business->categories->isNotEmpty())
                        <div class="mb-3 d-flex flex-wrap gap-1">
                            @foreach($business->categories as $category)
                                <span class="badge bg-light text-dark rounded-1 py-1 px-2 border border-light-subtle" style="font-size: 0.7rem;">
                                    {{ $category->name }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                    <!-- Subdomena: Zabezpieczona klasą text-truncate przed rozpychaniem ekranu na smartfonie -->
                    @if($business->subdomain)
                        <div class="mb-0 mt-auto pt-2 border-top border-light small text-secondary">
                            <a href="https://{{ $business->subdomain }}.{{ env('DOMAIN_NAME') }}" target="_blank" class="text-decoration-none text-primary fw-medium d-block text-truncate">
                                <i class="bi bi-globe me-1"></i>https://{{ $business->subdomain }}.{{ env('DOMAIN_NAME') }}
                            </a>
                        </div>
                    @endif
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

    <!-- Wyśrodkowana paginacja na mobile -->
    <div class="mt-4 d-flex justify-content-center justify-content-md-start">
        {{ $businesses->links() }}
    </div>
</div>