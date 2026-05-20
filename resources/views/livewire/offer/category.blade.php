@php
    // Wyciągamy aktualnie wybraną kategorię ze sluga z routingu (jeśli istnieje)
    $currentCategorySlug = request()->route('categorySlug');
    $currentCategory = $currentCategorySlug ? $categories->firstWhere('slug', $currentCategorySlug) : null;
    
    // Ustalamy, co mamy wyświetlić w tym widoku
    if ($currentCategory && $currentCategory->children && $currentCategory->children->isNotEmpty()) {
        // Jeśli wybrana kategoria ma dzieci -> pokazujemy jej dzieci
        $itemsToRender = $currentCategory->children;
        $parentCategory = $currentCategory;
    } elseif ($currentCategory && $currentCategory->parent_id) {
        // Jeśli jesteśmy na najniższym poziomie (brak dzieci) -> pokazujemy rodzeństwo
        $parentCategory = $currentCategory->parent;
        $itemsToRender = $parentCategory ? $parentCategory->children : $categories;
    } else {
        // Domyslnie: brak wybranej kategorii lub brak dzieci -> pokazujemy główny poziom
        $itemsToRender = $categories;
        $parentCategory = null;
    }
@endphp

<ul class="list-unstyled mb-0 d-flex flex-column gap-1 w-100">
    
    <!-- PRZYCISK POWROTU: Pokazuje się tylko, gdy zeszliśmy poziom niżej -->
    @if($parentCategory)
        <li class="w-100 mb-2">
            <a href="{{ $parentCategory->parent_id ? route('offers.index', $parentCategory->parent->slug) : route('offers.index') }}" 
               class="d-flex align-items-center gap-2 text-decoration-none py-1.5 px-3 small fw-bold text-muted bg-light rounded-3 transition-all text-hover-dark">
                <i class="bi bi-arrow-left-short fs-5"></i>
                <span>Back to @if($parentCategory->parent_id) {{ $parentCategory->parent->name }} @else All Categories @endif</span>
            </a>
        </li>
        
        <!-- Nagłówek informujący gdzie aktualnie jesteśmy -->
        <li class="px-3 py-1 text-uppercase text-muted fw-bold tracking-wider mb-1" style="font-size: 0.7rem;">
            Current: {{ $parentCategory->name }}
        </li>
    @endif

    <!-- JEDEN POZIOM KATEGORII -->
    @foreach($itemsToRender as $item)
        @php
            $isActive = ($currentCategorySlug === $item->slug);
        @endphp
        
        <li class="w-100">
            <a href="{{ route('offers.index', $item->slug) }}" 
               class="d-flex align-items-center justify-content-between text-decoration-none py-2 px-3 small rounded-3 transition-all {{ $isActive ? 'bg-primary-subtle text-primary fw-bold' : 'text-secondary text-hover-dark bg-hover-light' }}">
                
                <span class="text-truncate">{{ $item->name }}</span>
                
                <!-- Subtelna strzałka informująca, że ta kategoria ma podkategorie (zejście głębiej) -->
                @if($item->children && $item->children->isNotEmpty())
                    <i class="bi bi-chevron-right opacity-50" style="font-size: 0.7rem;"></i>
                @endif
            </a>
        </li>
    @endforeach
</ul>