<div class="col">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('offers.index') }}" class="text-decoration-none">Offers</a></li>
            @foreach($this->breadcrumb as $cat)
                <li class="breadcrumb-item"><a href="{{ route('offers.index', $cat->slug) }}" class="text-decoration-none">{{ $cat->name }}</a></li>
            @endforeach
            <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($offer->title, 40) }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        {{-- Lewa kolumna: Galeria i Opis --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow rounded-4 overflow-hidden mb-4">
                <div class="card-body p-4">
                    <h1 class="fw-bold h2 mb-4">{{ $offer->title }}</h1>

                   <livewire:gallery :images="$offer->images"/>
                    
                    <div class="offer-content py-3">
                        <h4 class="fw-semibold mb-3 border-start border-primary border-4 ps-3">Opis oferty</h4>
                        <div class="fs-5 text-secondary lh-base">
                            {!! nl2br(strip_tags($offer->content, '<a>')) !!}
                        </div>
                    </div>



                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('offers.index') }}" class="btn btn-light border px-4 py-2">
                    <i class="bi bi-arrow-left me-2"></i>Wróć do listy
                </a>
            </div>
        </div>

        {{-- Prawa kolumna: Sidebar --}}
        <div class="col-lg-4">
            <div class="sticky-top" style="top: 20px; z-index: 1000;">
                <div class="card border-0 shadow rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Informacje</h5>
                        
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-opacity-10 p-3 me-3">
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
                            <div class="bg-opacity-10 text-success p-3 me-3">
                                <i class="bi bi-tag fs-4"></i>
                            </div>
                            <div>
                                <p class="text-muted small mb-0">Kategoria</p>
                                <h6 class="mb-0 fw-bold">
                                    @if($offer->categories->isNotEmpty())
                                        <a href="{{ route('offers.index', $offer->categories->first()->slug) }}" class="text-decoration-none text-dark">
                                            {{ $offer->categories->first()->name }}
                                        </a>
                                    @else
                                        Brak kategorii
                                    @endif
                                </h6>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-0">
                            <div class="bg-opacity-10 p-3 me-3">
                                <i class="bi bi-calendar3 fs-4"></i>
                            </div>
                            <div>
                                <p class="text-muted small mb-0">Data dodania</p>
                                <h6 class="mb-0 fw-bold">{{ $offer->created_at->format('d.m.Y H:i') }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-lg-8">
            <div class="comments-section">
                <livewire:comments :model="$offer" />
            </div>
        </div>
    </div>
</div>
