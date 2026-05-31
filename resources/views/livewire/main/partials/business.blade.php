<div class="card h-100 bg-white border-0 rounded-4 overflow-hidden transition-hover shadow-sm d-flex flex-column position-relative">
    
    
    <span class="position-absolute top-0 end-0 m-3 badge text-dark">
        <i class="bi bi-buildings fs-5"></i>
        Firma
    </span>    

    <a href="{{ route('business.show', $item) }}" class="d-block flex-shrink-0 bg-light overflow-hidden" 
       style="min-height: 110px; height: 110px;"> 
       
       @if($item->images->isNotEmpty()) 
            <img loading="lazy" src="{{ asset('storage/' . $item->images->first()->path) }}" 
                 class="w-100 h-100 object-fit-cover" alt="{{ $item->name }}">
        @else
            <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted shadow">
                <i class="bi bi-file-earmark-post text-muted opacity-50" style="font-size: 2.5rem;"></i>
            </div>
        @endif
    </a>

    <div class="card-body p-3 p-lg-4 d-flex flex-column col-8 col-lg-12">
        <h6 class="card-title fw-bold mb-2 flex-grow-0">
            <a href="{{ route('business.show', $item)}}" class="text-decoration-none text-dark line-clamp-2 stretched-link hover-primary">
                {{ $item->name }}
                <i class="bi bi-chevron-right small opacity-50 ms-1"></i>
            </a>
        </h6>
        
        <p class="card-text text-secondary small mb-3 d-none d-sm-block line-clamp-2" style="font-size: 0.825rem;">
            {{ Str::limit($item->content ?? $item->description, 175) }}
        </p>

        @if($item->subdomain)
            <div class="mt-auto mb-2 small text-truncate position-relative" style="z-index: 2;">
                <a href="https://{{ $item->subdomain }}.{{ env('DOMAIN_NAME') }}" target="_blank" class="text-decoration-none text-primary fw-medium border-bottom border-primary border-opacity-25 pb-1">
                    <i class="bi bi-globe me-1"></i>{{ $item->subdomain }}.{{ env('DOMAIN_NAME') }}
                </a>
            </div>
        @endif
                            
        <div class="d-flex flex-wrap align-items-center justify-content-between pt-2 border-top mt-2">
            <div class="text-truncate" style="max-width: 60%;">
                @foreach($item->categories->take(1) as $category)
                    <span class="badge bg-primary-subtle text-primary rounded-pill px-2" style="font-size: 0.7rem;">
                        {{ $category->name }}
                    </span>
                @endforeach
            </div>
            <div class="d-flex align-items-center text-muted">
                <small class="fw-medium" style="font-size: 0.72rem;">
                    {{ $item->created_at->diffForHumans(null, true) }}
                </small>
            </div>
        </div>
    </div>
</div>