<div class="col">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('offers.index') }}" class="text-decoration-none">Offers</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($offer->title, 40) }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        {{-- Lewa kolumna: Galeria i Opis --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-body p-4">
                    <h1 class="fw-bold h2 mb-4">{{ $offer->title }}</h1>

                    @if($offer->images->isNotEmpty())
                        <div class="mb-4 bg-light rounded-3 overflow-hidden d-flex align-items-center justify-content-center" style="min-height: 400px; max-height: 600px;">
                            <a href="{{ asset('storage/' . $offer->images->first()->path) }}" target="_blank">
                                <img src="{{ asset('storage/' . $offer->images->first()->path) }}" class="img-fluid shadow-sm" alt="{{ $offer->title }}" style="max-height: 600px; object-fit: contain;">
                            </a>
                        </div>
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center rounded-3 mb-4" style="height: 300px;">
                            <i class="bi bi-image text-muted display-1"></i>
                        </div>
                    @endif

                    <div class="offer-content py-3">
                        <h4 class="fw-semibold mb-3 border-start border-primary border-4 ps-3">Opis oferty</h4>
                        <div class="fs-5 text-secondary lh-base">
                            {!! nl2br(strip_tags($offer->content, '<a>')) !!}
                        </div>
                    </div>

                    @if($offer->images->count() > 1)
                        <hr class="my-5">
                        <h4 class="fw-semibold mb-4 text-dark">Galeria zdjęć</h4>
                        <div class="row g-3">
                            @foreach($offer->images->skip(1) as $image)
                                <div class="col-6 col-md-4 col-lg-3">
                                    <a href="{{ asset('storage/' . $image->path) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $image->path) }}" class="img-fluid rounded-3 border shadow-sm hover-zoom" alt="{{ $offer->title }}" style="height: 150px; width: 100%; object-fit: cover;">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('offers.index') }}" class="btn btn-light border px-4 py-2 rounded-pill">
                    <i class="bi bi-arrow-left me-2"></i>Wróć do listy
                </a>
            </div>
        </div>

        {{-- Prawa kolumna: Sidebar --}}
        <div class="col-lg-4">
            <div class="sticky-top" style="top: 20px; z-index: 1000;">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Informacje</h5>
                        
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3 me-3">
                                <i class="bi bi-person-circle "></i>
                            </div>
                            <div>
                                <p class="text-muted small mb-0">Dodane przez</p>
                                <h6 class="mb-0 fw-bold">
                                    @if($offer->user)
                                        <a href="{{ route('user.profile', $offer->user) }}" class="text-decoration-none text-dark">{{ $offer->user->name }}</a>
                                    @else
                                        Anonim
                                    @endif
                                </h6>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-success bg-opacity-10 text-success rounded-circle p-3 me-3">
                                <i class="bi bi-tag fs-4"></i>
                            </div>
                            <div>
                                <p class="text-muted small mb-0">Kategoria</p>
                                <h6 class="mb-0 fw-bold">
                                    @if($offer->category)
                                        <span class="text-dark">{{ $offer->category->name }}</span>
                                    @else
                                        Brak kategorii
                                    @endif
                                </h6>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-0">
                            <div class="bg-info bg-opacity-10 text-info rounded-circle p-3 me-3">
                                <i class="bi bi-calendar3 fs-4"></i>
                            </div>
                            <div>
                                <p class="text-muted small mb-0">Data dodania</p>
                                <h6 class="mb-0 fw-bold">{{ $offer->created_at->format('d.m.Y H:i') }}</h6>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sekcja Głosów (Opcjonalnie, jeśli chcesz tu pokazać wynik) --}}
                <div class="card border-0 bg-primary text-white shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4 text-center">
                        <h6 class="text-uppercase small fw-bold opacity-75">Punkty zaufania</h6>
                        <div class="display-4 fw-bold">{{ $offer->votes_sum_value ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-lg-8">
            <div class="comments-section">
                <h3>Comments</h3>
                <livewire:comments :model="$offer" />
            </div>
        </div>
    </div>
</div>
