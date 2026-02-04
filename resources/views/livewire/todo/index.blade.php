<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Pomysły</h2>
        <a href="{{ route('todos.create') }}" class="btn btn-primary"><i class="bi bi-pencil-square"></i>Dodaj pomysł</a>
    </div>

    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <div class="row">
        @forelse ($todos as $todo)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="card-title mb-0">{{ $todo->title }}</h5>
                            <small class="text-muted text-nowrap ms-2">{{ $todo->created_at->diffForHumans() }}</small>
                        </div>
                        <div class="mt-auto d-flex justify-content-between align-items-center small text-muted">
                            <span>Autor: {{ $todo->user->first_name }} {{ $todo->user->last_name }}</span>
                            <span>Komentarze: {{ $todo->comments_count }}</span>
                        </div>
                        <a href="{{ route('todos.show', $todo->id) }}" class="stretched-link"></a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-secondary">
                    Brak pomysłów.
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $todos->links() }}
    </div>
