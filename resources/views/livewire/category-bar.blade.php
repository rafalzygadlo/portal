@php
    $isHorizontal = $orientation === 'horizontal';
    $containerClass = $isHorizontal ? 'd-flex flex-row flex-wrap gap-2' : 'd-flex flex-column gap-2';
    $itemClass = $isHorizontal ? 'w-auto' : 'w-100';
@endphp

<div class="{{ $containerClass }}">

<!-- PRZYCISK POWROTU: Pokazuje się tylko, gdy zeszliśmy poziom niżej -->
@if($currentCategory)
    @php
        $parentSlug = $currentCategory->parent?->slug
    @endphp
    <a href="#" wire:click.prevent="$dispatch('{{ $selectEvent }}', @js($parentSlug))"
        class="d-flex align-items-center gap-2 text-decoration-none py-1.5 px-3 small fw-bold text-muted bg-light rounded-3 transition-all text-hover-dark {{ $itemClass }}">
        <i class="bi bi-arrow-left-short fs-5"></i>
        <span>Back </span>
    </a>
@endif


    @foreach($categories as $item)
        @php
            $isActive = ($currentCategory?->id === $item->id);
        @endphp
        <div class="{{ $itemClass }}">
            <a href="#" wire:click.prevent="$dispatch('{{ $selectEvent }}','{{ $item->slug }}')"
            class="d-flex align-items-center justify-content-between text-decoration-none px-3 py-2 border transition-all {{ $itemClass }} {{ $isActive ? 'bg-primary text-white border-primary' : 'text-dark bg-white border-light-subtle bg-hover-light' }}">
                
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