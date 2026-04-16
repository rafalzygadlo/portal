<div class="business-dashboard container py-4">
    <div class="mb-4">
        <h1 class="h2 fw-bold">{{ $business->name }}</h1>
        <p class="text-muted">Reservations management panel</p>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success mb-4">
            {{ session('success') }}
        </div>
    @endif

    
        <div>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h4 fw-bold">Your services</h2>
                <button  wire:click="$dispatch('openServiceModal')" class="btn btn-primary"> + Add service </button>
            </div>

            @foreach ($services as $service)
                <div class="card mb-3 shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h5 class="fw-bold">{{ $service->name }}</h5>
                            <p class="text-muted small mb-2">{{ $service->description }}</p>
                            <div class="d-flex gap-3 small text-muted">
                                <span>⏱️ {{ $service->duration }} min</span>
                                @if ($service->price)
                                    <span>💰 {{ number_format($service->price, 2) }} PLN</span>
                                @endif
                                <span>⏳ Break: {{ $service->buffer }} min</span>
                            </div>
                        </div>
                        <div class="d-flex gap-2 ms-3">
                            <button 
                                wire:click="toggleServiceActive({{ $service->id }})"
                                class="btn btn-sm {{ $service->is_active ? 'btn-success' : 'btn-light border' }}"
                            >
                                {{ $service->is_active ? 'Active' : 'Inactive' }}
                            </button>
                            <button 
                                wire:click="openServiceModal({{ $service->id }})"
                                class="btn btn-sm btn-outline-primary"
                            >
                                Edit
                            </button>
                            <button 
                                wire:click="deleteService({{ $service->id }})"
                                class="btn btn-sm btn-outline-danger"
                                onclick="return confirm('Are you sure you want to delete?')"
                            >
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        
        <livewire:admin.business.service.create :business="$business" />
        </div>
    
        