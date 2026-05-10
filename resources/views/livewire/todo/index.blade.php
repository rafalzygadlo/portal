<div class="col">
    <style>
        .sticky-note {
            background: #fcfcc7; /* Delikatniejszy żółty */
            padding: 1rem;
            box-shadow: 5px 5px 10px rgba(0,0,0,0.1);
            transform: rotate(-1.5deg);
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            border: none;
            border-bottom-right-radius: 40px 5px;
            min-height: 180px; /* Lekko zmniejszona wysokość, aby lepiej pasowała do pastelowych kolorów */
            min-width: 300px;
            position: relative;
        }
        .sticky-note:nth-child(even) {
            transform: rotate(1.2deg);
            background: #fcfcd1; /* Inny, delikatny odcień żółtego */
        }
        .sticky-note:nth-child(3n) {
            transform: rotate(-0.8deg);
            background: #fdfde0; /* Jeszcze jaśniejszy, kremowy odcień */
        }
        .sticky-note:hover {
            transform: scale(1.05) rotate(0deg);
            box-shadow: 10px 10px 20px rgba(0,0,0,0.15);
            z-index: 10;
        }
    </style>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Todos</h2>
        <a href="{{ route('todos.create') }}" class="btn btn-primary"><i class="bi bi-pencil-square"></i>Add todo for admin</a>
    </div>

    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <div class="row">
        @forelse ($todos as $todo)
            <div class="col mb-4">
                <div class="card h-100 sticky-note">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="card-title mb-0 fw-bold">{{ $todo->title }}</h5>
                        </div>
                        <div class="mb-3">
                            <span class="badge bg-{{ $todo->getStatusColor() }}">{{ $todo->status }}</span>
                            <small class="text-muted d-block mt-1">{{ $todo->created_at->diffForHumans() }}</small>
                        </div>
                        <div class="mt-auto d-flex justify-content-between align-items-center small text-muted">
                            <span><i class="bi bi-person"></i> {{ $todo->user->first_name }}</span>
                            <span><i class="bi bi-chat"></i> {{ $todo->comments_count }}</span>
                        </div>
                        <a href="{{ route('todos.show', $todo->id) }}" class="stretched-link"></a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-secondary">
                    No ideas yet.
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $todos->links() }}
    </div>
