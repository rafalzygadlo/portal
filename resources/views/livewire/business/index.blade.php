<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Katalog firm</h2>
        <a href="{{ route('business.create') }}" class="btn btn-primary"><i class="bi bi-pencil-square"></i>Dodaj swoją firmę</a>
    </div>

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <div class="mb-4">
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a class="nav-link {{ is_null($category) ? 'active' : '' }}" href="#" wire:click.prevent="filterByCategory(null)">Wszystkie</a>
            </li>
            @foreach($categories as $cat)
            <li class="nav-item">
                <a class="nav-link {{ $category === $cat->slug ? 'active' : '' }}" href="#" wire:click.prevent="filterByCategory('{{ $cat->slug }}')">{{ $cat->name }}</a>
            </li>
            @endforeach
        </ul>
    </div>

    <div class="row">
        @forelse ($businesses as $business)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title mb-0">{{ $business->name }}</h5>
                            <small class="text-muted text-nowrap ms-2">{{ $business->created_at->diffForHumans() }}</small>
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

      <div class="mt-4">
        {{ $businesses->links() }}
    </div>
</div>
