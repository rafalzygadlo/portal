<div class="col-12 px-2 px-md-3">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center gap-2 mb-4">
        <h2 class="mb-1 text-center text-md-start fw-black">Todos</h2>

        <div class="d-flex gap-2">
        <div class="d-flex gap-2">
            <div class="dropdown flex-grow-1 flex-md-grow-0">
                <button class="btn btn-white border shadow-sm dropdown-toggle w-100 fw-medium d-flex align-items-center justify-content-between gap-2 px-3" 
                        type="button" data-bs-toggle="dropdown" aria-expanded="false" style="min-width: 200px; height: 42px; background: white;">
                    <span>
                        <i class="bi bi-sort-down me-2 text-primary"></i>
                        @if($sortBy === 'created_at') Najnowsze 
                        @elseif($sortBy === 'likes_count') Najwięcej polubień
                        @elseif($sortBy === 'comments_count') Najwięcej komentarzy
                        @elseif($sortBy === 'status') Według statusu
                        @else Sortowanie
                        @endif
                    </span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 mt-2">
                    <li><a class="dropdown-item py-2 {{ $sortBy === 'created_at' ? 'active' : '' }}" href="#" wire:click.prevent="$set('sortBy', 'created_at')"><i class="bi bi-clock me-2"></i>Najnowsze</a></li>
                    <li><a class="dropdown-item py-2 {{ $sortBy === 'likes_count' ? 'active' : '' }}" href="#" wire:click.prevent="$set('sortBy', 'likes_count')"><i class="bi bi-hand-thumbs-up me-2"></i>Najwięcej polubień</a></li>
                    <li><a class="dropdown-item py-2 {{ $sortBy === 'comments_count' ? 'active' : '' }}" href="#" wire:click.prevent="$set('sortBy', 'comments_count')"><i class="bi bi-chat-text me-2"></i>Najwięcej komentarzy</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item py-2 {{ $sortBy === 'status' ? 'active' : '' }}" href="#" wire:click.prevent="$set('sortBy', 'status')"><i class="bi bi-list-check me-2"></i>Według statusu</a></li>
                </ul>
            </div>
        </div>
        </div>
    </div>

    @if (session('message'))
        <div class="alert alert-success small py-2">
            {{ session('message') }}
        </div>
    @endif

    
    <div class="row g-2">
        @forelse ($todos as $todo)
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-3 overflow-hidden transition-all transition-hover">
                    <div class="card-body p-2 p-md-3">
                        <div class="row align-items-center g-3">
                            
                            <!-- Status i Tytuł -->
                            <div class="col-12 col-md-6 d-flex align-items-center gap-3">
                                <div class="flex-shrink-0 d-sm-block">
                                    <span class="badge bg-{{ $todo->getStatusColor() }} rounded-pill px-3 py-2">
                                        {{ $todo->status }}
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 fw-bold">
                                        <a href="{{ route('todos.show', $todo) }}" class="text-decoration-none text-dark hover-primary line-clamp-2 stretched-link">
                                            {{ $todo->title }}
                                        </a>
                                    </h6>
                                    <div class="d-flex align-items-center gap-2 mt-1 d-md-none">
                                        <i class="bi bi-person-circle me-1"></i>
                                        <span class="text-truncate">{{ $todo->user->first_name }}</span>
                                        <span class="mx-1">•</span>
                                        <small class="text-muted" style="font-size: 0.75rem;">{{ $todo->created_at->diffForHumans(null, true) }}</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Autor i Czas (Desktop) -->
                            <div class="col-md-3 d-none d-md-flex align-items-center text-muted small">
                                <i class="bi bi-person-circle me-2"></i>
                                <span class="text-truncate">{{ $todo->user->first_name }}</span>
                                <span class="mx-2">•</span>
                                <span class="text-nowrap">{{ $todo->created_at->diffForHumans(null, true) }}</span>
                            </div>

                            <!-- Statystyki -->
                            <div class="col-12 col-md-3 d-flex justify-content-start justify-content-md-end gap-3 text-muted small">
                                <span title="Komentarze"><i class="bi bi-chat me-1"></i>{{ $todo->comments_count }}</span>
                                {{-- <span title="Wyświetlenia"><i class="bi bi-eye me-1"></i>{{ $todo->views_count }}</span> --}}
                                <span title="Polubienia"><i class="bi bi-hand-thumbs-up me-1"></i>{{ $todo->likes_count }}</span>
                            </div>

                        </div>
                        <div class="mt-3 d-flex justify-content-end border-top pt-2">
                            <livewire:favorite :model="$todo" :key="'favorite-todo-list-'.$todo->id" />
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