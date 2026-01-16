<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Ogłoszenia</h2>
        <a href="{{ route('announcements.create') }}" class="btn btn-primary">Dodaj ogłoszenie</a>
    </div>

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <div class="list-group">
        @forelse ($announcements as $announcement)
            <a href="{{ route('announcements.show', $announcement->id) }}" class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">{{ $announcement->title }}</h5>
                    <small>{{ $announcement->created_at->diffForHumans() }}</small>
                </div>
                <p class="mb-1">{{ Str::limit($announcement->content, 150) }}</p>
                <small>Kategoria: {{ $announcement->category->name }}</small>
                <small class="ms-2">Autor: {{ $announcement->user->name }}</small>
            </a>
        @empty
            <div class="alert alert-secondary">
                Brak ogłoszeń.
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $announcements->links() }}
    </div>
</div>
