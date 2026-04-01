<div class="business-dashboard container py-4">
    <div class="mb-4">
        <h1 class="h2 fw-bold">{{ $business->name }}</h1>
        <p class="text-muted">Panel zarządzania rezerwacjami</p>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success mb-4">
            {{ session('success') }}
        </div>
    @endif

    
        <div>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h4 fw-bold">Twoje usługi</h2>
                <button 
                    wire:click="openServiceModal"
                    class="btn btn-primary"
                >
                    + Dodaj usługę
                </button>
            </div>

            @foreach ($services as $service)
                <div class="card mb-3 shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h5 class="fw-bold">{{ $service->name }}</h5>
                            <p class="text-muted small mb-2">{{ $service->description }}</p>
                            <div class="d-flex gap-3 small text-muted">
                                <span>⏱️ {{ $service->duration_minutes }} min</span>
                                @if ($service->price)
                                    <span>💰 {{ number_format($service->price, 2) }} zł</span>
                                @endif
                                <span>⏳ Przerwa: {{ $service->buffer_minutes }} min</span>
                            </div>
                        </div>
                        <div class="d-flex gap-2 ms-3">
                            <button 
                                wire:click="toggleServiceActive({{ $service->id }})"
                                class="btn btn-sm {{ $service->is_active ? 'btn-success' : 'btn-light border' }}"
                            >
                                {{ $service->is_active ? 'Aktywna' : 'Nieaktywna' }}
                            </button>
                            <button 
                                wire:click="openServiceModal({{ $service->id }})"
                                class="btn btn-sm btn-outline-primary"
                            >
                                Edytuj
                            </button>
                            <button 
                                wire:click="deleteService({{ $service->id }})"
                                class="btn btn-sm btn-outline-danger"
                                onclick="return confirm('Na pewno usunąć?')"
                            >
                                Usuń
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    