<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Pomysły</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTodoModal">
            Dodaj pomysł
        </button>
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
                        <a href="{{ route('todo.show', $todo->id) }}" class="stretched-link"></a>
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

    <!-- Modal -->
    <div class="modal fade" id="createTodoModal" tabindex="-1" aria-labelledby="createTodoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createTodoModalLabel">Dodaj nowy pomysł</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <livewire:todo.create />
                </div>
            </div>
        </div>
    </div>
</div>
