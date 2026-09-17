<div>

    {{-- ========================================= --}}
    {{-- KATEGORIE + STRZAŁKI --}}
    {{-- ========================================= --}}

    <div class="position-relative">

        {{-- LEWO --}}
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


        {{-- PASEK KATEGORII --}}
        <div
            id="category-scroll"
            class="category-scroll d-flex align-items-center flex-nowrap gap-2 overflow-auto px-5"
        >

            {{-- POWRÓT --}}
            @if($this->currentCategoryModel)

                <button
                    type="button"
                    wire:click="goBack"
                    class="category-item d-flex align-items-center gap-2 py-2 px-3
                           small fw-bold text-muted bg-light rounded-3 border-0
                           flex-shrink-0 text-nowrap"
                >
                    <i class="bi bi-arrow-left-short fs-5"></i>
                    <span>Powrót</span>
                </button>

            @endif


            {{-- KATEGORIE --}}
            @foreach($categories as $category)

                @if($category->children()->exists())

                    {{-- MA PODKATEGORIE --}}
                    <button
                        type="button"
                        wire:click="selectCategory({{ $category->id }})"
                        class="category-item d-flex align-items-center gap-2
                               px-3 py-2 border rounded-3 flex-shrink-0
                               text-nowrap text-dark bg-white border-light-subtle"
                    >
                        <span>{{ $category->name }}</span>

                        <i class="bi bi-chevron-right opacity-50"></i>
                    </button>

                @else

                    {{-- KATEGORIA KOŃCOWA --}}
                    <label
                        wire:click.prevent="selectCategory({{ $category->id }})"
                        class="category-item d-flex align-items-center gap-2
                               px-3 py-2 border rounded-3 flex-shrink-0
                               text-nowrap text-dark bg-white border-light-subtle"
                        style="cursor: pointer;"
                    >

                        <input
                            type="checkbox"
                            class="form-check-input m-0"
                            @checked(in_array($category->id, $value))
                            readonly
                        >

                        <span>{{ $category->name }}</span>

                    </label>

                @endif

            @endforeach

        </div>


        {{-- PRAWO --}}
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

    </div>


    {{-- ========================================= --}}
    {{-- WYBRANE KATEGORIE --}}
    {{-- ========================================= --}}

    @if($this->selectedCategoriesCollection->isNotEmpty())

        <div class="mt-3">

            {{-- DANE DO FORMULARZA --}}
            @foreach($selectedCategoriesCollection as $selected)

                <input
                    type="hidden"
                    name="categories[]"
                    value="{{ $selected->id }}"
                >

            @endforeach


            {{-- BADGE --}}
            @foreach($selectedCategoriesCollection as $selected)

                <span class="badge text-bg-primary me-1 mb-1">

                    {{ $selected->name }}

                    <button
                        type="button"
                        wire:click="selectCategory({{ $selected->id }})"
                        class="btn-close btn-close-white ms-1"
                        style="font-size: .6em;"
                        aria-label="Usuń"
                    ></button>

                </span>

            @endforeach

        </div>

    @endif


    {{-- ========================================= --}}
    {{-- CSS --}}
    {{-- ========================================= --}}

    <style>
        /*
         * Pasek kategorii:
         * - jedna linia
         * - możliwość przewijania
         * - scrollbar niewidoczny
         */
        .category-scroll {
            width: 100%;
            height: 52px;

            scrollbar-width: none;
            -ms-overflow-style: none;

            scroll-behavior: smooth;
        }

        .category-scroll::-webkit-scrollbar {
            display: none;
        }


        /*
         * Nie pozwalamy elementom kategorii
         * zmniejszać się przy małej szerokości.
         */
        .category-item {
            flex-shrink: 0;
            white-space: nowrap;
        }
    </style>

</div>