<div class="col">
    <!-- Header -->
    <div class="bg-primary text-white rounded-3 p-5 mb-5">
        <div class="d-flex justify-content-between align-items-start mb-4">
            <div class="flex-grow-1">
                <h1 class="h1 fw-bold mb-2">{{ $company->name }}</h1>
                <p class="text-white-50">{{ $company->description }}</p>
            </div>
            
        </div>

        <!-- Informacje kontaktowe -->
        <div class="row g-4">
            <div class="col-md-4">
                <p class="small text-white-50">📍 Adres</p>
                <p class="fw-semibold">{{ $company->address }}</p>
            </div>
            @if($company->phone)
                <div class="col-md-4">
                    <p class="small text-white-50">📞 Telefon</p>
                    <p class="fw-semibold"><a href="tel:{{ $company->phone }}" class="text-white">{{ $company->phone }}</a></p>
                </div>
            @endif
            @if($company->website)
                <div class="col-md-4">
                    <p class="small text-white-50">🌐 Strona</p>
                    <p class="fw-semibold"><a href="{{ $company->website }}" target="_blank" class="text-white">Visit</a></p>
                </div>
            @endif
        </div>
    </div>

    <!-- Kategorie -->
    @if($company->categories->isNotEmpty())
        <div class="mb-5">
            <h3 class="h5 fw-semibold mb-3">Kategorie</h3>
            <div class="d-flex flex-wrap gap-2">
                @foreach($company->categories as $category)
                    <span class="badge bg-info-subtle text-info-emphasis rounded-pill px-3 py-2">
                        {{ $category->name }}
                    </span>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Reservation services -->
    @if(count($services) > 0)
        <div class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h2 fw-bold">Available services</h2>
                @if($company->subdomain)
                    <a href="{{ route('company.booking.services', ['company' => $company]) }}" class="btn btn-primary px-4 py-2">
                        Zarezerwuj termin
                    </a>
                @endif
            </div>

            <div class="row g-4">
                @foreach($services as $service)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow">
                            <div class="card-body d-flex flex-column">
                                <h3 class="h5 card-title fw-bold mb-2">{{ $service->name }}</h3>
                                <p class="card-text text-muted small mb-4">{{ $service->description }}</p>
                                
                                <div class="mb-4 mt-auto">
                                    <p class="small text-body mb-1">⏱️ <strong>Czas:</strong> {{ $service->duration }} minut</p>
                                    @if($service->price)
                                        <p class="small text-body mb-1">💰 <strong>Price:</strong> {{ number_format($service->price, 2) }} PLN</p>
                                    @endif
                                    <p class="small text-body mb-1">⏳ <strong>Break:</strong> {{ $service->buffer }} min</p>
                                </div>

                                @if($company->subdomain)
                                    <a href="{{ route('company.booking.services', ['company' => $company]) }}" class="btn btn-primary-subtle w-100">
                                        Zarezerwuj
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if($company->resources()->where('type', 'equipment')->where('is_active', true)->exists())
        <div class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h4 fw-bold mb-0">Equipment rental</h2>
                <a href="{{ route('company.booking.equipment', ['company' => $company]) }}" class="btn btn-outline-primary">
                    Rent equipment
                </a>
            </div>
            <p class="text-muted mb-0">Rent available equipment independently from a service booking.</p>
        </div>
    @endif

    <!-- Godziny pracy -->
    @php
        $dayNames = [
            'mon' => 'Monday',
            'tue' => 'Wtorek',
            'wed' => 'Wednesday',
            'thu' => 'Czwartek',
            'fri' => 'Friday',
            'sat' => 'Sobota',
            'sun' => 'Niedziela',
        ];
    @endphp

    <div class="card mb-5">
        <div class="card-header">
            <h3 class="h5 card-title fw-bold my-1">Godziny pracy</h3>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @foreach($dayNames as $key => $dayName)
                    @php
                        $hours = $company->getCompanyHours()[$key];
                    @endphp
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="fw-medium">{{ $dayName }}</span>
                            <span class="text-muted">
                                @if($hours['closed'] ?? false)
                                    <span class="text-danger">Closed</span>
                                @else
                                    {{ $hours['open'] }} - {{ $hours['close'] }}
                                @endif
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Owner administration panel -->
    @if($isOwner)
        {{$company->subdomain}}
            <div class="alert alert-warning border-start-lg border-warning mb-5">
            <h5 class="alert-heading fw-bold mb-2">Panel Administracyjny</h5>
             {{-- <a href="{{ route('dashboard.company', $company->user ) }}" class="btn btn-warning"> --}}
                Manage reservations
            </a>
        </div>
    @endif

    <!-- Opis biznesu -->
    <div class="card mb-5">
        <div class="card-header">
            <h3 class="h5 card-title fw-bold my-1">O biznesie</h3>
        </div>
        <div class="card-body">
            <p class="text-body">
                {{ nl2br(e($company->description)) }}
            </p>
        </div>
    </div>

    <!-- Owner information -->
    <div class="bg-light rounded-3 p-4 mb-5">
        <p class="small text-muted">
            Biznes dodany: <strong>{{ $company->created_at->format('d.m.Y H:i') }}</strong><br>
            {{--  Owner: <strong><a href="{{ route('user.profile', $company->owner) }}" class="text-primary">{{ $company->owner->first_name }} {{ $company->owner->last_name }}</a></strong>  --}}
        </p>
    </div>

    <!-- Comments -->
    <div class="mb-5">
        <h3 class="h2 fw-bold mb-4">Comments i opinie</h3>
        <livewire:comments :model="$company" />
    </div>
</div>
