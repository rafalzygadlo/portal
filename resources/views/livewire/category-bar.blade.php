<div class="position-relative">

    {{-- STRZAŁKA LEWO --}}
    <button type="button" class="btn btn-light shadow-sm position-absolute start-0 top-50 translate-middle-y"
        style="z-index: 10;" onclick="document.getElementById('category-scroll').scrollBy({
            left: -300,
            behavior: 'smooth'
        })">
        <i class="bi bi-chevron-left"></i>
    </button>


    {{-- KATEGORIE --}}
    <div id="category-scroll" class="d-flex flex-row flex-nowrap gap-2 overflow-auto px-5 category-scroll"
        style="scroll-behavior: smooth;">

        {{-- POWRÓT --}}
        @if($currentCategory)
            @php
                $parentSlug = $currentCategory->parent?->slug
            @endphp

            <a href="{{ route($module . '.index', ['categorySlug' => $parentSlug]) }}" {{--
                wire:click.prevent="$dispatch('{{ $selectEvent }}', @js($parentSlug))" --}}
                class="d-flex align-items-center gap-2 text-decoration-none py-2 px-3 small fw-bold text-muted bg-light rounded-3 flex-shrink-0 text-nowrap">
                <i class="bi bi-arrow-left-short fs-5"></i>
                <span>Back</span>
            </a>
        @endif


        {{-- KATEGORIE --}}
        @foreach($categories as $item)

            @php
                $isActive = ($currentCategory?->id === $item->id);
            @endphp

            <a href="{{ route($module . '.index', ['categorySlug' => $item->slug]) }}" wire:navigate {{--
                wire:click.prevent="$dispatch('{{ $selectEvent }}','{{ $item->slug }}')" --}} class="
                
                d-flex
                        align-items-center
                        gap-2
                        text-decoration-none
                        px-3
                        py-2
                        rounded-3
                        flex-shrink-0
                        text-nowrap

                        {{ $isActive
            ? 'bg-primary text-white border-secondary'
            : 'text-dark border-light-subtle bg-hover-light'
                        }}
                    ">

                <span>
                    {{ $item->name }}
                </span>

                @if($item->children_count > 0)
                    <i class="bi bi-chevron-right opacity-50"></i>
                @endif

            </a>

        @endforeach

    </div>


    {{-- STRZAŁKA PRAWO --}}
    <button type="button" class="btn btn-light shadow-sm position-absolute end-0 top-50 translate-middle-y"
        style="z-index: 10;" onclick="document.getElementById('category-scroll').scrollBy({
            left: 300,
            behavior: 'smooth'
        })">
        <i class="bi bi-chevron-right"></i>
    </button>

    {{-- UKRYCIE SCROLLBARA --}}
    <style>
        .category-scroll {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .category-scroll::-webkit-scrollbar {
            display: none;
        }
    </style>

</div>