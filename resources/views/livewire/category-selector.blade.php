<div class="position-relative">

{{--
    <div class="input-group">
        <span class="input-group-text">
            <i class="bi bi-search"></i>
        </span>

        <input
            type="text"
            class="form-control"
            placeholder="Wpisz kategorię..."
            wire:model.live.debounce.300ms="categorySearch"
        >
    </div>


    @if($this->categorySearchResults->isNotEmpty())

        <div class="list-group position-absolute w-100 shadow-sm"
             style="z-index: 1000;">

            @foreach($this->categorySearchResults as $category)

                <button
                    type="button"
                    class="list-group-item list-group-item-action"
                    wire:click="selectCategory({{ $category->id }})"
                >
                    <i class="bi bi-folder me-2"></i>
                    {{ $category->name }}
                </button>

            @endforeach

        </div>

    @endif
--}}


    {{-- STRZAŁKA LEWO --}}
    <button
        type="button"
        class="btn btn-light shadow-sm position-absolute start-0 top-50 translate-middle-y"
        style="z-index: 10;"
        onclick="document.getElementById('category-scroll').scrollBy({
            left: -300,
            behavior: 'smooth'
        })"
    >
        <i class="bi bi-chevron-left"></i>
    </button>


    {{-- KATEGORIE --}}
    <div
        id="category-scroll"
        class="d-flex flex-row flex-nowrap gap-2 overflow-auto px-5 category-scroll"
        style="scroll-behavior: smooth;"
    >

        {{-- POWRÓT --}}
        @if($this->currentCategoryModel)

            <button
                type="button"
                wire:click="goBack"
                class="d-flex align-items-center gap-2 text-decoration-none py-2 px-3 small fw-bold text-muted bg-light rounded-3 border-0 flex-shrink-0 text-nowrap"
            >
                <i class="bi bi-arrow-left-short fs-5"></i>
                <span>Powrót</span>
            </button>

        @endif


        {{-- KATEGORIE --}}
        @foreach($categories as $category)

            @if($category->children()->exists())

                {{-- Kategoria posiada podkategorie --}}
                <button
                    type="button"
                    wire:click="selectCategory({{ $category->id }})"
                    class="d-flex align-items-center gap-2 text-decoration-none px-3 py-2 border rounded-3 flex-shrink-0 text-nowrap text-dark bg-white border-light-subtle"
                >
                    <span>{{ $category->name }}</span>

                    <i class="bi bi-chevron-right opacity-50"></i>
                </button>

            @else

                {{-- Kategoria końcowa --}}
                <label
                    wire:click.prevent="selectCategory({{ $category->id }})"
                    class="d-flex align-items-center gap-2 text-decoration-none px-3 py-2 border rounded-3 flex-shrink-0 text-nowrap text-dark bg-white border-light-subtle"
                    style="cursor: pointer;"
                >

                    <input
                        type="checkbox"
                        class="form-check-input m-0"
                        @if(in_array($category->id, $value)) checked @endif
                        readonly
                    >

                    <span>{{ $category->name }}</span>

                </label>

            @endif

        @endforeach

    </div>


    {{-- STRZAŁKA PRAWO --}}
    <button
        type="button"
        class="btn btn-light shadow-sm position-absolute end-0 top-50 translate-middle-y"
        style="z-index: 10;"
        onclick="document.getElementById('category-scroll').scrollBy({
            left: 300,
            behavior: 'smooth'
        })"
    >
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
