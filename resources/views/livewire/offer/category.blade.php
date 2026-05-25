

    
    <!-- PRZYCISK POWROTU: Pokazuje się tylko, gdy zeszliśmy poziom niżej -->
    {{--      @if($categorySlug)
        <li class="w-100 mb-2">
            <a href="{{ $categorySlug->parent_id ? route('offers.index', $categorySlug->parent->slug) : route('offers.index') }}" 
               class="d-flex align-items-center gap-2 text-decoration-none py-1.5 px-3 small fw-bold text-muted bg-light rounded-3 transition-all text-hover-dark">
                <i class="bi bi-arrow-left-short fs-5"></i>
                <span>Back to @if($categorySlug->parent_id) {{ $categorySlug->parent->name }} @else All Categories @endif</span>
            </a>
        </li>
        
        <!-- Nagłówek informujący gdzie aktualnie jesteśmy -->
        <li class="px-3 py-1 text-uppercase text-muted fw-bold tracking-wider mb-1" style="font-size: 0.7rem;">
            Current: {{ $categorySlug->name }}
        </li>
    @endif
    --}}


    <!-- JEDEN POZIOM KATEGORII -->
      <div class="d-flex flex-nowrap flex-md-wrap gap-2 overflow-x-auto pb-2 pb-md-0" style="scrollbar-width: none; -webkit-overflow-scrolling: touch;"></div>
    @foreach($categories as $item)
        @php
            $isActive = ($categorySlug === $item->slug);
        @endphp
          <div class1="flex-shrink-1">
      
        
            <a href="{{ route('offers.index', $item->slug) }}" 
               class1="d-flex align-items-center justify-content-between text-decoration-none text-primary bg-hover-light' }}">
                
                <span class="text-truncate">{{ $item->name }}</span>
                
                <!-- Subtelna strzałka informująca, że ta kategoria ma podkategorie (zejście głębiej) -->
                @if($item->children && $item->children->isNotEmpty())
                    <i class="bi bi-chevron-right opacity-50" style="font-size: 0.7rem;"></i>
                @endif
            </a>
        
</div>
        
    @endforeach
</div>