<div>
    <h3 class="fw-semibold mb-3 h5">Wybierz usługę</h3>
    <div class="row g-3">
        @forelse ($services as $service)
            <div class="col-md-6">
                <div 
                    class="service-card p-3 rounded h-100 d-flex flex-column {{ $selectedServiceId == $service->id ? 'active' : '' }}"
                    wire:click="selectService('{{ $service->id }}')"
                >
                    <div class="d-flex align-items-center">
                        <i class="bi bi-gem fs-2 text-primary me-3"></i>
                        <div>
                            <h6 class="fw-bold mb-0">{{ $service->name }}</h6>
                            <small class="text-muted">{{ $service->duration_minutes }} min</small>
                        </div>
                    </div>
                     @if ($service->price)
                        <div class="mt-auto text-end fs-5 fw-bold pt-2">
                            {{ number_format($service->price, 2) }} zł
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-secondary">Brak dostępnych usług do rezerwacji.</div>
            </div>
        @endforelse
    </div>
    @error('selectedServiceId')
        <div class="text-danger small mt-2 d-block">{{ $message }}</div>
    @enderror
    <div class="d-flex justify-content-between mt-4">
        <button type="button" wire:click="nextStep" class="btn btn-primary">
            Dalej
        </button>
                    
    </div>
       <style>
                .service-card {
                    cursor: pointer;
                    border: 2px solid #e9ecef;
                    transition: all 0.2s ease-in-out;
                }
                .service-card:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                    border-color: var(--bs-primary);
                }
                .service-card.active {
                    border-color: var(--bs-primary);
                    background-color: #f0f8ff;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                }
            </style>
</div>
