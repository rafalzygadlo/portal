<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Strona główna</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('business.index') }}" class="text-decoration-none">Katalog firm</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($business->name, 30) }}</li>
                </ol>
            </nav>

            <div class="card border-0 overflow-hidden">
                <div class="card-body">
                    <h1 class="fw-bold mb-3">{{ $business->name }}</h1>

                    <div class="d-flex align-items-center text-muted mb-4 border-bottom pb-3">
                        <i class="bi bi-person-circle me-1"></i> {{ $business->user->first_name }}
                        <span class="mx-2">&bull;</span>
                        <i class="bi bi-calendar3 me-1"></i> {{ $business->created_at->format('d.m.Y') }}
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-8">
                            <p class="mb-2"><i class="bi bi-geo-alt me-2"></i> <strong>Adres:</strong> {{ $business->address }}</p>
                            @if($business->phone)
                                <p class="mb-2"><i class="bi bi-telephone me-2"></i> <strong>Telefon:</strong> {{ $business->phone }}</p>
                            @endif
                            @if($business->website)
                                <p class="mb-2"><i class="bi bi-globe me-2"></i> <strong>Strona WWW:</strong> <a href="{{ $business->website }}" target="_blank" class="link-primary">{{ $business->website }}</a></p>
                            @endif
                        </div>
                        <div class="col-md-4">
                            @if($business->categories->isNotEmpty())
                                <div class="mb-2">
                                    @foreach($business->categories as $category)
                                        <span class="badge bg-light text-dark border me-1">{{ $category->name }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mb-4">
                        {{ nl2br(e($business->description)) }}
                    </div>

                    <a href="{{ route('business.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left"></i> Wróć do listy
                    </a>
                </div>
            </div>

            <hr class="my-4">

            <div class="comments-section">
                <h3>Komentarze</h3>
                <livewire:comments :model="$business" />
            </div>
        </div>
    </div>
</div>
