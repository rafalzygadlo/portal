<div class="d-flex flex-nowrap flex-md-wrap gap-2 overflow-x-auto pb-2 pb-md-0"
    style="scrollbar-width: none; -webkit-overflow-scrolling: touch;">

<!-- PRZYCISK POWROTU: Pokazuje się tylko, gdy zeszliśmy poziom niżej -->
@if($this->categorySlug && $currentCategory)
    <a href="{{ $currentCategory->parent_id ? route($this->route, $currentCategory->parent->slug) : route($this->route) }}"
        class="d-flex align-items-center gap-2 text-decoration-none py-1.5 px-3 small fw-bold text-muted bg-light rounded-3 transition-all text-hover-dark">
        <i class="bi bi-arrow-left-short fs-5"></i>
        <span>Back </span>
    </a>
@endif



    @foreach($categories as $item)
        @php
            $isActive = ($categorySlug === $item->slug);
        @endphp
        <div class="flex-shrink-0">
            <a href="{{ route($this->route, $item->slug) }}"
                class="d-flex align-items-center justify-content-between text-decoration-none px-3 py-2 border transition-all {{ $isActive ? 'bg-primary text-white border-primary' : 'text-dark bg-white border-light-subtle bg-hover-light' }}">
                
                <div class="d-flex align-items-center gap-2 overflow-hidden">
                    <span class="text-truncate" style="max-width: 130px;">{{ $item->name }}</span>

                </div>

                    <!-- Subtelna strzałka informująca, że ta kategoria ma podkategorie (zejście głębiej) -->
                    @if($item->children_count > 0)
                        <i class="bi bi-chevron-right opacity-50" style="font-size: 0.7rem;"></i>
                    @endif
            </a>
        </div>
    @endforeach
</div>