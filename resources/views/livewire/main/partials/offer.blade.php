
 <div class="card h-100 bg-white border-0 rounded-4 overflow-hidden transition-hover shadow-sm d-flex flex-column position-relative">

    <!-- MINIATURKA / ZDJĘCIE -->
    <a href="{{ route('offers.show', $item) }}"
        class="position-relative d-block flex-shrink-0 bg-light overflow-hidden"
        style="min-height: 110px; max-height: 120px;">
        @if (!empty($item->images))

            @if($item->images->isNotEmpty())
                <img loading="lazy" src="{{ asset('storage/' . $item->images->skip(0)->first()->path) }}"
                    class="justify-content-center align-items-center  w-100" alt="{{ $item->title }}">
            @else
                <div
                    class="w-100 h-100 d-flex align-items-center justify-content-center text-muted">
                    <i class="bi bi-file-earmark-post text-muted opacity-50" style="font-size: 2.5rem;"></i>
                </div>
            @endif
        @endif
    </a>

     <span class="position-absolute top-0 end-0 m-3 badge text-dark">
        <i class="bi bi-megaphone fs-5"></i>
        Ogłoszenie
    </span>

    <!-- TREŚĆ KARTY -->
    <div class="card-body p-3 p-lg-4 d-flex flex-column col-8 col-lg-12">
        <!-- Tytuł -->
         <a href="{{ route('offers.show', $item) }}" class="text-decoration-none text-dark hover-primary stretched-link">
            <h6 class="card-title fw-bold mb-1 mb-lg-2 line-clamp-2">
                {{ $item->title }}
            </h6>
        </a>

        <!-- Krótki opis (Ukryty na bardzo małych ekranach dla oszczędności miejsca) -->
        <p class="card-text text-secondary small flex-grow-1 mb-2 d-none d-sm-block line-clamp-2"
            style="font-size: 0.825rem;">
            {{ Str::limit($item->content ?? $item->description, 75) }}
        </p>

        {{ $item->price ? 'Cena: ' . $item->price . ' zł' : '' }}
        <!-- Kategorie i data dodania -->
        <div
            class="mt-auto d-flex flex-wrap align-items-center justify-content-between gap-1 pt-1 border-top">
            <div class="text-truncate" style="max-width: 60%;">
                @foreach($item->categories->take(1) as $category) {{-- Na mobile bierzemy tylko 1 tag, żeby nie rozbić
                    układu --}}
                    <span class="badge bg-primary-subtle text-primary border-0 rounded-pill px-2.5 py-1"
                        style="font-size: 0.7rem;">
                        {{ $category->name }}
                    </span>
                @endforeach
            </div>

            <small class="text-muted fw-medium">
                {{ $item->created_at->diffForHumans(null, true) }} {{-- Krótka forma czasu np. "2 dni temu" --}}
            </small>
        </div>
        <div class="mt-2 d-flex justify-content-end">
            <livewire:favorite :model="$item" :key="'favorite-main-offer-'.$item->id" />
        </div>
    </div>

</div>