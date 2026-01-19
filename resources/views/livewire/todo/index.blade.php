<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Pomysły</h2>
        <a href="{{ route('todo.create') }}" class="btn btn-primary">Dodaj pomysł</a>
    </div>

    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <div class="list-group">
        @forelse ($todos as $todo)
            <a href="{{ route('todo.show', $todo->id) }}" class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">{{ $todo->title }}</h5>
                    <small>{{ $todo->created_at->diffForHumans() }}</small>
                </div>
                <div class="d-flex justify-content-between">
                    <small>Autor: {{ $todo->user->name }}</small>
                    <small>Komentarze: {{ $todo->comments_count }}</small>
                </div>
            </a>
        @empty
            <div class="alert alert-secondary">
                Brak pomysłów.
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $todos->links() }}
    </div>
</div>
