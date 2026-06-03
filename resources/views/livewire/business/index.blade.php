<div class="col-12 px-1 px-md-3" x-data="{ view: 'grid' }">
    
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

    <div class="mb-4 p-3 p-md-4 rounded-1 bg-light">
        <div class="col-lg-12 col-xl-12 mb-4">
            <div class="card border-0 rounded-4 shadow-sm bg-white sticky-top">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-2 fw-bold text-dark d-flex align-items-center gap-2" style="font-size: 1.05rem;">
                    <i class="bi bi-folder2-open text-primary"></i> Categories
                </div>
                <div class="card-body px-4 pb-4 pt-2">
                    <livewire:sidebar route="business.index" :categorySlug="$categorySlug" :currentCategory="$currentCategory" :key="'side-'.$categorySlug" />
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 g-md-4">
        @forelse ($businesses as $business)
            <div :class="view === 'grid' ? 'col-12 col-md-6 col-xl-4' : 'col-12'">
                
                <div class="h-100 p-3 p-md-4 d-flex flex-column bg-white rounded-1 border border-secondary-subtle shadow-sm">
                    
                    <div class="d-flex justify-content-between align-items-start mb-3 gap-2">
                        <div class="text-truncate me-2">
                            <h5 class="mb-1 fw-semibold text-break fs-6 fs-md-5">{{ $business->name }}</h5>
                            <div class="text-secondary" style="font-size: 0.75rem;">
                                <i class="bi bi-calendar3 me-1"></i>{{ $business->created_at->diffForHumans() }}
                            </div>
                        </div>
                        <a href="{{ route('business.show', $business) }}" class="btn btn-sm btn-outline-primary flex-shrink-0">View</a>
                    </div>

                    <p class="text-secondary mb-3" style="font-size: 0.9rem; line-height: 1.4;">
                        {{ Str::limit($business->description, 120) }}
                    </p>

                    @if($business->categories->isNotEmpty())
                        <div class="mb-3 d-flex flex-wrap gap-1">
                            @foreach($business->categories as $category)
                                <span class="badge bg-light text-dark rounded-1 py-1 px-2 border border-light-subtle" style="font-size: 0.7rem;">
                                    {{ $category->name }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                    @if($business->subdomain)
                        <div class="mb-0 mt-auto pt-2 border-top border-light small text-secondary">
                            <a href="https://{{ $business->subdomain }}.{{ env('DOMAIN_NAME') }}" target="_blank" class="text-decoration-none text-primary fw-medium d-block text-truncate">
                                <i class="bi bi-globe me-1"></i>{{ $business->subdomain }}.{{ env('DOMAIN_NAME') }}
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

    <div class="mt-4 d-flex justify-content-center justify-content-md-start">
        {{ $businesses->links() }}
    </div>
</div>