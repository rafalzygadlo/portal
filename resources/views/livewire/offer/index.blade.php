<div class="col-12 px-1 px-md-3">

    {{-- NAGŁÓWEK --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-black fs-2 tracking-tight text-dark mb-1">
                Oferty
            </h2>

            <p class="text-muted small mb-0 d-none d-sm-block">
                Znajdź interesujące oferty w swojej okolicy.
            </p>
        </div>
    </div>


    {{-- STATUS --}}
    @if (session('status'))
        <div class="alert alert-success border-0 rounded-4 shadow-sm p-3 mb-4 d-flex align-items-center gap-2"
            style="background-color: #e6f4ea; color: #137333;">

            <i class="bi bi-check-circle-fill fs-5"></i>

            <div class="fw-medium small">
                {{ session('status') }}
            </div>

        </div>
    @endif


    {{-- BREADCRUMB --}}
    <livewire:breadcrumb module="offers" :category="$currentCategory" :key="'bc-' . $categorySlug" />


    {{-- KATEGORIE --}}
    <div class="mb-4">

        <details class="border-top border-bottom py-3" open>

            <summary class="d-flex align-items-center justify-content-between gap-2 fw-bold text-dark"
                style="cursor:pointer; list-style:none;">

                <span class="d-flex align-items-center gap-2">

                    <span
                        class="d-flex align-items-center justify-content-center rounded-circle bg-primary-subtle text-primary"
                        style="width:34px;height:34px;">
                        <i class="bi bi-grid"></i>
                    </span>

                    Kategorie

                </span>


                <span class="d-flex align-items-center gap-2 text-muted small">

                    <span class="d-none d-sm-inline">
                        Zwiń
                    </span>

                    <i class="bi bi-chevron-down"></i>

                </span>

            </summary>


            <div class="pt-3">

                <livewire:category-bar orientation="horizontal" module="offers" :currentCategory="$currentCategory" :key="'side-' . $categorySlug" />

            </div>

        </details>

    </div>


    {{-- OFERTY --}}
    <div>

        @forelse ($offers as $offer)

            <article wire:key="offer-{{ $offer->id }}" class="row g-3 py-3 border-bottom">

                {{-- ZDJĘCIE --}}
                <div class="col-4 col-sm-3 col-md-3 col-lg-2">

                    <a href="{{ route('offer.show', $offer) }}" class="d-block text-decoration-none">

                        @if($offer->images->isNotEmpty())

                            <img loading="lazy" src="{{ asset('storage/' . $offer->images->first()->getThumbPath()) }}"
                                class="img-fluid rounded object-fit-cover w-100" style="aspect-ratio: 4 / 3;"
                                alt="{{ $offer->title }}">

                        @else

                            <div class="bg-light rounded d-flex align-items-center justify-content-center w-100"
                                style="aspect-ratio: 4 / 3;">
                                <i class="bi bi-image fs-2 text-muted"></i>
                            </div>

                        @endif

                    </a>

                </div>


                {{-- TREŚĆ --}}
                <div class="col">

                    <div class="d-flex justify-content-between align-items-start gap-2">

                        <div class="min-width-0">

                            {{-- KATEGORIA + PROMOWANE --}}
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-1">

                                @if($offer->isPromoted())

                                    <span class="small text-warning-emphasis fw-semibold">

                                        <i class="bi bi-star-fill me-1"></i>

                                        Promowane

                                    </span>

                                @endif


                                @if($offer->categories->isNotEmpty())

                                    <span class="small text-muted">

                                        {{ $offer->categories->pluck('name')->join(', ') }}

                                    </span>

                                @endif

                            </div>


                            {{-- TYTUŁ --}}
                            <h3 class="h5 mb-2">

                                <a href="{{ route('offer.show', $offer) }}" class="text-decoration-none text-dark">
                                    {{ Str::limit($offer->title, 90) }}
                                </a>

                            </h3>


                            {{-- OPIS --}}
                            @if(!empty($offer->description))

                                <p class="text-muted small mb-2 d-none d-md-block">

                                    {{ Str::limit(strip_tags($offer->description), 140) }}

                                </p>

                            @endif


                            {{-- CENA --}}
                            <div class="fw-bold fs-5 mb-2">

                                @if($offer->price !== null)

                                    {{ number_format($offer->price, 2, ',', ' ') }} zł

                                @else

                                    <span class="fw-normal text-muted fs-6">
                                        Cena do uzgodnienia
                                    </span>

                                @endif

                            </div>


                            {{-- INFORMACJE --}}
                            <div class="d-flex flex-wrap align-items-center gap-2 text-muted small">

                                <span>
                                    <i class="bi bi-clock me-1"></i>
                                    {{ $offer->created_at->diffForHumans() }}
                                </span>


                                @if(!empty($offer->location))

                                    <span>•</span>

                                    <span>
                                        <i class="bi bi-geo-alt me-1"></i>
                                        {{ $offer->location }}
                                    </span>

                                @endif

                            </div>

                        </div>


                        {{-- ULUBIONE --}}
                        <div class="d-flex">
                            <div class="mb-2">
                            <livewire:promote :model="$offer" :key="'promote-offer-' . $offer->id" />
                            </div>
                            <div class="mb-2">
                            <livewire:favorite :model="$offer" :key="'favorite-offer-' . $offer->id" />
                            </div>

                        </div>

                    </div>

                </div>

            </article>


        @empty

            {{-- BRAK OFERT --}}
            <div class="text-center py-5">

                <div class="mb-3">

                    <i class="bi bi-search fs-1 text-muted"></i>

                </div>


                <h5 class="fw-bold text-dark">
                    Brak ofert
                </h5>


                <p class="text-muted small mb-0">
                    W tej kategorii nie ma jeszcze żadnych ofert.
                </p>

            </div>

        @endforelse

    </div>


    {{-- LOAD MORE --}}
    @if($offers->hasMorePages())

        <div class="text-center py-4">

            <div wire:loading.remove wire:target="loadMore">

                <button wire:click="loadMore" class="btn btn-outline-primary px-5 rounded-pill fw-bold">

                    <i class="bi bi-plus-circle me-2"></i>

                    Załaduj więcej

                </button>

            </div>


            <div wire:loading wire:target="loadMore">

                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">
                        Ładowanie...
                    </span>
                </div>


                <div class="mt-2 text-secondary small">
                    Ładowanie kolejnych ofert...
                </div>

            </div>

        </div>

    @endif

</div>