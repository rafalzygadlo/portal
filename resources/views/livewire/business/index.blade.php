<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Katalog firm</h1>
        <a href="{{ route('business.create') }}" class="btn btn-primary">Dodaj swoją firmę</a>
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

    <div class="list-group">
        @forelse ($businesses as $business)
            <a href="{{ route('business.show', $business->slug) }}" class="list-group-item list-group-item-action">
                <div class="d-flex w-100 align-items-center">
                    <div class="flex-grow-1">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">{{ $business->name }}</h5>
                            <small>{{ $business->created_at->diffForHumans() }}</small>
                        </div>
                        <p class="mb-1">{{ Str::limit($business->description, 150) }}</p>
                        <small>{{ $business->address }}</small>
                    </div>
                    <div class="ms-4">
                        <livewire:business.vote :business="$business" :key="$business->id" />
                    </div>
                </div>
            </a>
        @empty
            <div class="alert alert-secondary">
                Brak firm w tej kategorii.
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $businesses->links() }}
    </div>
</div>
