<div class="col">
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
            <div class="col-3 mb-4">
                <div class="card h-100 shadow border-0">
                    <div class="card-body d-flex flex-column ">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <a href="{{ route('todos.show', $todo->id) }}" class="text-decoration-none text-dark">
                                <h5 class="card-title mb-0 fw-bold">{{ $todo->title }}</h5>
                            </a>
                        </div>
                        <div class="mb-3">
                            <span class="badge bg-{{ $todo->getStatusColor() }}">{{ $todo->status }}</span>
                            <small class="text-muted d-block mt-1">{{ $todo->created_at->diffForHumans() }}</small>
                        </div>
                        <div class="mt-auto d-flex justify-content-between align-items-center small text-muted">
                            <span><i class="bi bi-person"></i> {{ $todo->user->first_name }}</span>    
                            <div class="d-flex gap-3">
                                <span><i class="bi bi-chat"></i> {{ $todo->comments_count }}</span>
                                <span><i class="bi bi-eye"></i> {{ $todo->views_count }}</span>
                                <span><i class="bi bi-hand-thumbs-up"></i> {{ $todo->likes_count }}</span>
                            </div>
                        </div>
                        
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
