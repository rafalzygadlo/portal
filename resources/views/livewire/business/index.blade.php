<div class="col">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Business</h2>
        <a href="{{ route('business.create') }}" class="btn btn-outline-dark btn-sm">Add your business</a>
    </div>

    @if (session('status'))
        <div class="alert alert-success rounded-1">
            {{ session('status') }}
        </div>
    @endif

    <div class="mb-4 p-4 rounded-1 bg-light">
        <h5 class="fw-semibold mb-3">Filtruj firmy</h5>
        <div class="d-flex flex-wrap gap-2">
            @foreach($categories as $cat)
                <div>
                    <input type="checkbox" class="btn-check" value="{{ $cat->slug }}"
                           id="cat-{{ $cat->id }}" wire:model.live="selectedCategories" autocomplete="off">
                    <label class="btn btn-sm btn-outline-secondary" for="cat-{{ $cat->id }}">{{ $cat->name }}</label>
                </div>
            @endforeach
        </div>
    </div>

    <div class="row g-4">
        @forelse ($businesses as $business)
            <div class="col-12 col-md-6 col-xl-4">
                <div class="h-100 p-4 d-flex flex-column bg-light border rounded-1 border-secondary-subtle">
                    <div class="d-flex justify-content-between align-items-start mb-3 gap-3">
                        <div>
                            <h5 class="mb-1 fw-semibold">{{ $business->name }}</h5>
                            <div class="text-secondary small">{{ $business->created_at->diffForHumans() }}</div>
                        </div>
                        <span class="badge bg-light text-dark rounded-1 py-1 px-2 small">
                            {{ $business->is_approved ? 'Approved' : 'Review' }}
                        </span>
                    </div>

                    <p class="text-secondary mb-3">{{ Str::limit($business->description, 120) }}</p>

                    @if($business->categories->isNotEmpty())
                        <div class="mb-3 d-flex flex-wrap gap-2">
                            @foreach($business->categories as $category)
                                <span class="badge bg-light text-dark rounded-1 py-1 px-2 small">{{ $category->name }}</span>
                            @endforeach
                        </div>
                    @endif

                    <div class="mt-auto">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <small class="text-secondary">{{ $business->address }}</small>
                            <livewire:business.vote :model="$business" :key="$business->id" />
                        </div>
                    </div>

                    @if($business->subdomain)
                        <p class="mb-0 mt-3 small text-secondary">
                            <a href="https://{{ $business->subdomain }}.{{ env('DOMAIN_NAME') }}" target="_blank" class="text-decoration-none text-dark">{{ $business->subdomain }}</a>
                        </p>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-secondary rounded-1">
                    Brak firm w tej kategorii.
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $businesses->links() }}
    </div>
</div>