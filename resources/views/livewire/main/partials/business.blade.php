<div class="card h-100 bg-white border-0 rounded-4 overflow-hidden transition-rotate shadow-sm d-flex flex-column position-relative">
    
    <span class="position-absolute top-0 end-0 m-3 badge bg-light text-dark border">
        Firma
    </span>    

    <a href="" class="d-block flex-shrink-0 col-4 col-lg-12 bg-light overflow-hidden" 
       style="min-height: 110px; height: 110px;"> 
       
       @if($item->images->isNotEmpty()) 
            <img loading="lazy" src="{{ asset('storage/' . $item->images->first()->path) }}" 
                 class="w-100 h-100 object-fit-cover" alt="{{ $item->name }}">
        @else
            <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted">
                <i class="bi bi-image" style="font-size: 1.8rem;"></i>
            </div>
        @endif
    </a>

    <div class="card-body p-3 p-lg-4 d-flex flex-column col-8 col-lg-12">
        <h6 class="card-title fw-bold mb-2">
            <a href="" class="text-decoration-none text-dark line-clamp-2">{{ $item->name }}</a>
        </h6>
        
        <p class="card-text text-secondary small mb-3 d-none d-sm-block line-clamp-2" style="font-size: 0.825rem;">
            {{ Str::limit($item->content ?? $item->description, 175) }}
        </p>

        @if($item->subdomain)
            <div class="mt-auto mb-2 small text-truncate">
                <a href="https://{{ $item->subdomain }}.{{ env('DOMAIN_NAME') }}" target="_blank" class="text-decoration-none text-primary fw-medium">
                    <i class="bi bi-globe me-1"></i>{{ $item->subdomain }}.{{ env('DOMAIN_NAME') }}
                </a>
            </div>
        @endif
                            
        <div class="d-flex flex-wrap align-items-center justify-content-between pt-2 border-top">
            <div class="text-truncate" style="max-width: 60%;">
                @foreach($item->categories->take(1) as $category)
                    <span class="badge bg-primary-subtle text-primary rounded-pill px-2" style="font-size: 0.7rem;">
                        {{ $category->name }}
                    </span>
                @endforeach
            </div>
            <small class="text-muted fw-medium" style="font-size: 0.72rem;">
                {{ $item->created_at->diffForHumans(null, true) }}
            </small>
        </div>
    </div>
</div>