<div class="col-12 px-2 px-md-3">
    <!-- NAGŁÓWEK: Na smartfonie układ pionowy (flex-column), przycisk na pełną szerokość (w-100). Od tabletu (md) wraca do linii (flex-row) -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center gap-2 mb-4">
        <h2 class="mb-0 text-center text-md-start fw-bold">Todos</h2>
        <a href="{{ route('todos.create') }}" class="btn btn-primary d-flex align-items-center justify-content-center gap-2">
            <i class="bi bi-pencil-square"></i>
            <span>Add todo for admin</span>
        </a>
    </div>

    @if (session('message'))
        <div class="alert alert-success small py-2">
            {{ session('message') }}
        </div>
    @endif

    <!-- GRID: Na smartfonie col-12 (jedna karta pod drugą, ale zgrabna) lub col-sm-6 (dwie obok siebie, jeśli ekran jest ciut większy). Na komputerze col-md-3 (cztery w rzędzie) -->
    <div class="row g-3 g-md-4">
        @forelse ($todos as $todo)
            <div class="col-12 col-sm-6 col-md-3">
                <div class="card h-100 shadow-sm border-0 rounded-3 overflow-hidden">
                    <div class="card-body d-flex flex-column p-3">
                        
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <a href="{{ route('todos.show', $todo->id) }}" class="text-decoration-none text-dark blended-link">
                                <h5 class="card-title mb-0 fw-bold fs-6 fs-md-5 text-break">{{ $todo->title }}</h5>
                            </a>
                        </div>
                        
                        <!-- Mini statystyki stanu i czasu -->
                        <div class="mb-3 d-flex flex-wrap align-items-center gap-2">
                            <span class="badge bg-{{ $todo->getStatusColor() }} small-badge">{{ $todo->status }}</span>
                            <small class="text-muted text-nowrap" style="font-size: 0.75rem;">
                                <i class="bi bi-clock me-1"></i>{{ $todo->created_at->diffForHumans() }}
                            </small>
                        </div>
                        
                        <!-- STOPKA KARTY: Ikony i autor zoptymalizowane pod małe ekrany, żeby nie wyszły poza krawędź -->
                        <div class="mt-auto pt-2 border-top border-light d-flex justify-content-between align-items-center text-muted" style="font-size: 0.8rem;">
                            <span class="text-truncate me-1" style="max-width: 80px;">
                                <i class="bi bi-person"></i> {{ $todo->user->first_name }}
                            </span>    
                            <div class="d-flex gap-2 gap-md-3 flex-wrap justify-content-end">
                                <span class="text-nowrap"><i class="bi bi-chat"></i> {{ $todo->comments_count }}</span>
                                <span class="text-nowrap"><i class="bi bi-eye"></i> {{ $todo->views_count }}</span>
                                <span class="text-nowrap"><i class="bi bi-hand-thumbs-up"></i> {{ $todo->likes_count }}</span>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-secondary text-center py-4">
                    <i class="bi bi-inbox d-block fs-2 mb-2 text-muted"></i>
                    No ideas yet.
                </div>
            </div>
        @endforelse
    </div>

    <!-- Paginacja: wyśrodkowana na telefonie -->
    <div class="mt-4 d-flex justify-content-center justify-content-md-start">
        {{ $todos->links() }}
    </div>
</div>