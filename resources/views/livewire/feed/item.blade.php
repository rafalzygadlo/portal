<div class="card h-100 bg-white border-0 rounded-4 overflow-hidden transition-hover shadow-sm d-flex flex-column position-relative{{ ! empty($isPromoted) ? ' border border-warning shadow-lg' : '' }}">
   
    @if(! empty($isPromoted))
        <span class="position-absolute top-0 start-0 m-3 badge bg-warning-subtle text-warning border border-warning rounded-pill px-2 py-1" style="z-index: 10;">
            <i class="bi bi-megaphone-fill me-1"></i>
            Promoted
        </span>
    @endif

    <a href="{{ route($item->type . '.show', $item->slug) }}" class1="d-block flex-shrink-0 bg-light">
        
            @if($images->isNotEmpty())
                <img loading="lazy" src="{{ asset('storage/' . $images->skip(0)->first()->path) }}"
                    class="justify-content-center align-items-center  w-100 h-100" alt="{{ $data->title }}">
            @else
                <div
                    class="w-100 h-100 d-flex align-items-center justify-content-center text-muted position-absolute position-lg-relative">

                    <i class="bi bi-file-earmark-post text-muted opacity-50" style="font-size: 2.5rem;"></i>
                </div>
            @endif
    </a>
    
    
    <span class="position-absolute top-0 end-0 m-3 badge text-dark ">
         <i class="bi bi-sticky fs-5"></i>
        Artykuł
    </span>    
 
    <!-- TREŚĆ KARTY -->
    <div class="card-body p-3 p-lg-4 d-flex flex-column col-8 col-lg-12">
        <!-- Tytuł -->
        <h6 class="card-title fw-bold mb-1 mb-lg-2 flex-grow-0">
            <a href="{{ route1($item->type . '.show', $item->slug) }}" class="text-decoration-none text-dark stretched-link hover-primary line-clamp-2">
                {{ $item->title }}
                <i class="bi bi-chevron-right small opacity-50 ms-1"></i>
            </a>
        </h6>

        <!-- Kategorie i data dodania -->
        <div
            class="mt-auto d-flex flex-wrap align-items-center justify-content-between gap-1 pt-1 border-top">
           
            <small class="text-muted fw-medium" style="font-size: 0.72rem;">
              
            </small>
        </div>
        <div class="mt-2 d-flex justify-content-end">
            <livewire:favorite :model="$item" :key="'favorite-feed-'.$item->type.'-'.$item->item_id" />
        </div>
    </div>

</div>