<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Katalog firm</h2>
        <a href="{{ route('business.create') }}" class="btn btn-primary"><i class="bi bi-pencil-square"></i>Dodaj swoją
            firmę</a>
    </div>

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif


    <div class="row">
        <div class="col-md-3 ">

            <div class="col-md-12 ">
                <h5 class="mb-3">Kategorie</h5>
                <div class="card p-3">
                    <div class="row">
                        @foreach($categories as $cat)

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="{{ $cat->slug }}"
                                    id="cat-{{ $cat->id }}" wire:model.live="selectedCategories">
                                <label class="form-check-label" for="cat-{{ $cat->id }}">
                                    {{ $cat->name }}
                                </label>
                            </div>

                        @endforeach
                    </div>
                </div>
            </div>

        </div>
        <div class="col-md-9">
            <div class="row g-3">
            @forelse ($businesses as $business)
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body d-flex1 flex-column1">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0">{{ $business->name }}</h5>
                                <small
                                    class="text-muted text-nowrap ms-2">{{ $business->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="card-text text-muted small mb-3">{{ Str::limit($business->description, 100) }}</p>
                            <div class="mt-auto d-flex justify-content-between align-items-end">
                                <small class="text-muted">{{ $business->address1 }}</small>
                                <div style="position: relative; z-index: 2;">
                                    <livewire:business.vote :model="$business" :key="$business->id" />
                                </div>
                            </div>
                            <a href="{{ route('business.show', $business->slug) }}" class="stretched-link"></a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-secondary">
                        Brak firm w tej kategorii.
                    </div>
                </div>
            @endforelse
        </div>
        </div>
    </div>
    <div class="mt-4">
        {{ $businesses->links() }}
    </div>
</div>