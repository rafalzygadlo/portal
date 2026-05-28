<div class="card h-100 bg-white border-0 rounded-4 overflow-hidden transition-hover shadow-sm d-flex flex-column position-relative">
    
    <span class="position-absolute top-0 end-0 m-3 badge text-dark">
         <i class="bi bi-check2-square fs-5"></i>
        Task
    </span>

    <div class="card-body p-4 d-flex flex-column">
        
        <div class="mb-3">
            <h4 class="h5 fw-bold mb-2">
                <a href="{{ route('todos.show', $item)}}" class="text-decoration-none text-dark hover-primary">{{ $item->title }}</a>
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
    </div>
</div>