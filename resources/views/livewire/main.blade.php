<div class="container-fluid">
    <!-- BANER INFORMACYJNY + PRZYCISK DODAWANIA -->
    <div class="row justify-content-center mb-4">
        <div class="col-12 col-xl-8">
            <div class="card border-0 bg-light rounded-4 p-4 text-center shadow-sm position-relative">
            
                <h4 class="fw-black text-dark mb-2">Strumień Aktywności</h4>
                <p class="text-secondary small mx-auto mb-4" style="max-width: 600px;">
                    Tutaj znajdziesz wszystkie nowości, ogłoszenia i artykuły pojawiające się na portalu. 
                    Będziemy Cię tu również na bieżąco informować o nowych funkcjach i ważnych wydarzeniach!
                </p>

                <div class="dropdown">
                    <button class="btn btn-primary btn-lg 1rounded-pill px-4 py-2.5 shadow-sm dropdown-toggle fw-bold" 
                            type="button" 
                            data-bs-toggle="dropdown" 
                            aria-expanded="false" 
                            style="font-size: 0.95rem;">
                        <i class="bi bi-plus-circle-fill me-2"></i> Dodaj nową treść
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 mt-2 p-2">
                        <li>
                            <a class="dropdown-item py-2.5 rounded-2 d-flex align-items-center gap-2" 
                               wire:click="$dispatch('openModal', ['offer.create','Dodaj ogłoszenie'])" 
                               href="#">
                                <i class="bi bi-megaphone-fill text-primary fs-5"></i> 
                                <span class="fw-medium">Dodaj ogłoszenie</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2.5 rounded-2 d-flex align-items-center gap-2" 
                               wire:click="$dispatch('openModal', ['business.create','Dodaj firmę'])" 
                               href="#">
                                <i class="bi bi-buildings-fill text-success fs-5"></i> 
                                <span class="fw-medium">Dodaj firmę</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2.5 rounded-2 d-flex align-items-center gap-2" 
                               wire:click="$dispatch('openModal', ['todo.create','Dodaj zadanie dla administratora'])" 
                               href="#">
                                <i class="bi bi-check-circle-fill text-info fs-5"></i> 
                                <span class="fw-medium">Dodaj zadanie dla administratora</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- STRUMIEŃ POSTÓW (FEED) -->
    <div class="row g-3 g-md-4">
        @foreach ($feed as $item)
            <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                <!-- KARTA: Układ horyzontalny na mobile (row), wertykalny na desktopie (flex-lg-column) -->
                <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden flex-row flex-lg-column transition-all hover-shadow">
                    
                    <!-- MINIATURKA / ZDJĘCIE -->
                    <a href="{{ route('offers.show', $item) }}" 
                       class="position-relative d-block flex-shrink-0 col-4 col-lg-12 bg-light overflow-hidden" 
                       style="min-height: 110px; max-height: 100%; height: auto; lg-height: 180px;">
                        
                        @if($item->images->isNotEmpty())               
                            <img loading="lazy" 
                                 src="{{ asset('storage/' . $item->images->skip(0)->first()->path) }}" 
                                 class="justify-content-center align-items-center object-fit-cover w-100 h-100"
                                 alt="{{ $item->title }}" 
                                 >
                        @else
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center border-end border-lg-0 border-bottom-lg text-muted position-absolute position-lg-relative"
                                 >
                                <i class="bi bi-image" style="font-size: 1.8rem;"></i>
                            </div>
                        @endif
                    </a>

                    <!-- TREŚĆ KARTY -->
                    <div class="card-body p-3 p-lg-4 d-flex flex-column col-8 col-lg-12">
                        <!-- Tytuł -->
                        <h6 class="card-title fw-bold mb-1 mb-lg-2">
                            <a href="{{ route('offers.show', $item) }}" class="text-decoration-none text-dark hover-primary line-clamp-2">
                                {{ $item->title }}
                            </a>
                        </h6>
                        
                        <!-- Krótki opis (Ukryty na bardzo małych ekranach dla oszczędności miejsca) -->
                        <p class="card-text text-secondary small flex-grow-1 mb-2 d-none d-sm-block line-clamp-2" style="font-size: 0.825rem;">
                            {{ Str::limit($item->content ?? $item->description, 75) }}
                        </p>
                        
                        <!-- Kategorie i data dodania -->
                        <div class="mt-auto d-flex flex-wrap align-items-center justify-content-between gap-1 pt-1 border-top border-light">
                            <div class="text-truncate" style="max-width: 60%;">
                                @foreach($item->categories->take(1) as $category) {{-- Na mobile bierzemy tylko 1 tag, żeby nie rozbić układu --}}
                                    <span class="badge bg-primary-subtle text-primary border-0 rounded-pill px-2.5 py-1" style="font-size: 0.7rem;">
                                        {{ $category->name }}
                                    </span>
                                @endforeach
                            </div>
                            <small class="text-muted fw-medium" style="font-size: 0.72rem;">
                                {{ $item->created_at->diffForHumans(null, true) }} {{-- Krótka forma czasu np. "2 dni temu" --}}
                            </small>
                        </div>
                    </div>

                </div>
            </div>
        @endforeach
    </div>
</div>