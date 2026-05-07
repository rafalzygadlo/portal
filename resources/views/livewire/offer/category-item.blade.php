@foreach($categories as $category)
    @php
        $hasChildren = $category->children && $category->children->isNotEmpty();
        $collapseId = "category-collapse-" . $category->id;
        $isActive = request('category') == $category->id;

        // Sprawdzamy, czy kategoria jest w ścieżce breadcrumb (jest przodkiem wybranej kategorii)
        $isInPath = collect($breadcrumb ?? [])->contains('id', $category->id);
        $shouldExpand = $isActive || $isInPath;
    @endphp
    <li class="py-1">
        <div class="d-flex align-items-center">
            @if($hasChildren)
                <a class="text-muted me-1 text-decoration-none" 
                   data-bs-toggle="collapse" 
                   href="#{{ $collapseId }}" 
                   role="button" 
                   aria-expanded="{{ $shouldExpand ? 'true' : 'false' }}">
                    <i class="bi bi-chevron-right small collapse-icon" style="font-size: 0.7rem;"></i>
                </a>
            @else
                <i class="bi bi-dot small text-muted me-1"></i>
            @endif
            
            <a href="?category={{ $category->id }}" 
               class="category-link text-decoration-none text-dark small {{ $isActive ? 'fw-bold text-primary' : '' }}">
                {{ $category->name }}
            </a>
        </div>
        @if($hasChildren)
            <div class="collapse {{ $shouldExpand ? 'show' : '' }}" id="{{ $collapseId }}">
                <ul class="list-unstyled ps-3 mt-1 border-start">
                    @include('livewire.offer.category-item', ['categories' => $category->children])
                </ul>
            </div>
        @endif
    </li>
@endforeach