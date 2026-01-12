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

    <div class="list-group">
        @forelse ($businesses as $business)
            <a href="{{ route('business.show', $business->slug) }}" class="list-group-item list-group-item-action flex-column align-items-start">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">{{ $business->name }}</h5>
                    <small>{{ $business->created_at->diffForHumans() }}</small>
                </div>
                <p class="mb-1">{{ Str::limit($business->description, 150) }}</p>
                <small>{{ $business->address }}</small>
            </a>
        @empty
            <div class="alert alert-secondary">
                Brak firm w katalogu. Bądź pierwszy i <a href="{{ route('business.create') }}">dodaj swoją</a>!
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $businesses->links() }}
    </div>
</div>
