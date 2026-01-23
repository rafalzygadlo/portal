<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Offers</h2>
        <a href="{{ route('offers.create') }}" class="btn btn-primary">Add Offer</a>
    </div>

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <div class="list-group">
        @forelse ($offers as $offer)
            <a href="{{ route('offers.show', $offer->id) }}" class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">{{ $offer->title }}</h5>
                    <small>{{ $offer->created_at->diffForHumans() }}</small>
                </div>
                <p class="mb-1">{{ Str::limit($offer->content, 150) }}</p>
                <small>Category: {{ $offer->category->name }}</small>
                <small class="ms-2">By: {{ $offer->user->name }}</small>
            </a>
        @empty
            <div class="alert alert-secondary">
                No offers available.
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $offers->links() }}
    </div>
</div>
