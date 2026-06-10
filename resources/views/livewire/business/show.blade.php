<div class="col">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb small">
                    <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('business.index') }}" class="text-decoration-none">Business directory</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($business->name, 30) }}</li>
                </ol>
            </nav>

            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
                        <h1 class="fw-bold mb-0 display-6">{{ $business->name }}</h1>
                        @if($business->categories->isNotEmpty())
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($business->categories as $category)
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10 rounded-pill px-3 py-2">
                                        {{ $category->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="d-flex flex-wrap justify-content-between align-items-center border-bottom pb-3 mb-4 gap-3">
                        <div class="text-muted d-flex align-items-center flex-wrap gap-2">
                            @if($business->owner)
                                <span class="d-flex align-items-center">
                                    <i class="bi bi-person-circle me-1"></i> 
                                    <a href="{{ route('user.profile', $business->owner) }}" class="text-decoration-none text-dark fw-medium">{{ $business->owner->name }}</a>
                                </span>
                                <span class="text-muted d-none d-sm-inline">•</span>
                            @endif
                            <span class="d-flex align-items-center">
                                <i class="bi bi-calendar3 me-1"></i> Dodano: {{ $business->created_at->format('d.m.Y') }}
                            </span>
                        </div>
                        <div>
                            <livewire:favorite :model="$business" :key="'favorite-business-'.$business->id" />
                        </div>
                    </div>

                    <div class="row g-4 mb-5">
                        <div class="col-md-8">
                            <div class="article-content fs-5 lh-lg text-secondary">
                                {!! nl2br(e($business->description)) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="bg-light rounded-3 p-4 border-0">
                                <h5 class="fw-bold mb-3 small text-uppercase text-muted tracking-wider">Dane kontaktowe</h5>
                                <p class="mb-2 d-flex align-items-start"><i class="bi bi-geo-alt me-2 text-primary"></i> <span><strong>Adres:</strong><br>{{ $business->address }}</span></p>
                                @if($business->phone)
                                    <p class="mb-2"><i class="bi bi-telephone me-2 text-primary"></i> <strong>Telefon:</strong> {{ $business->phone }}</p>
                                @endif
                                 @if($business->subdomain)
                                    <p class="mb-0"><i class="bi bi-globe me-2 text-primary"></i> <strong>WWW:</strong> <a href="https://{{ $business->subdomain }}.{{ env('DOMAIN_NAME') }}" target="_blank" class="text-decoration-none fw-bold text-primary">{{ $business->subdomain }}</a></p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 border-top pt-5">
                        <livewire:comments :model="$business" />
                    </div>
                </div>
                <div class="card-footer bg-light border-0 p-4">
                     <a href="{{ route('business.index') }}" class="btn btn-light border shadow-sm px-4">
                        <i class="bi bi-arrow-left me-2"></i> Powrót do katalogu
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
