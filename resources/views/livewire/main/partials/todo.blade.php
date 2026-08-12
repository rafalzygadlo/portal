<div class="card h-100 bg-white border-0 rounded-4 overflow-hidden transition-hover shadow-sm d-flex flex-column position-relative{{ ! empty($isPromoted) ? ' border border-warning shadow-lg' : '' }}">
    @if(! empty($isPromoted))
        <span class="position-absolute top-0 start-0 m-3 badge bg-warning-subtle text-warning border border-warning rounded-pill px-2 py-1" style="z-index: 10;">
            <i class="bi bi-megaphone-fill me-1"></i>
            Promoted
        </span>
    @endif

    <span class="position-absolute top-0 end-0 m-3 badge text-dark">
         <i class="bi bi-check2-square fs-5"></i>
        Task
    </span>

    <div class="card-body p-4 d-flex flex-column">
        
        <div class="mb-3">
            <h4 class="h5 fw-bold mb-2 flex-grow-0">
                <a href="{{ route('todo.show', $item)}}" class="text-decoration-none text-dark stretched-link hover-primary">
                    {{ $item->title }}
                    <i class="bi bi-chevron-right small opacity-50 ms-1"></i>
                </a>
            </h4>
            
            <span class="badge bg-{{ $item->getStatusColor() }} text-white px-2 py-1 rounded-pill small">
                {{ $item->status }}
            </span>
        </div>

        <p class="card-text text-secondary small mb-4 flex-grow-1">
            {{ Str::limit($item->content ?? $item->description, 75) }}
        </p>

        <div class="mt-auto pt-3 border-top text-end">
            <small class="text-muted fw-medium " >
                {{ $item->created_at->diffForHumans(null, true) }}
            </small>
        </div>
        <div class="mt-2 d-flex justify-content-end">
            <livewire:favorite :model="$item" :key="'favorite-main-todo-'.$item->id" />
        </div>
    </div>
</div>