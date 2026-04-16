<div class="business-dashboard container py-4">
    <div class="mb-4">
        <h1 class="h2 fw-bold">{{ $business->name }}</h1>
        <p class="text-muted">Reservation management panel</p>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Tabs -->
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a href="{{ route('dashboard.business', ['business' => $business, 'tab' => 'services']) }}" class="nav-link {{ $tab === 'services' ? 'active' : '' }}">
                Services
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('dashboard.business', ['business' => $business, 'tab' => 'reservations']) }}" class="nav-link {{ $tab === 'reservations' ? 'active' : '' }}">
                Reservations
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.business.resources.index', $business) }}" class="nav-link">
                Resources
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('dashboard.business', ['business' => $business, 'tab' => 'settings']) }}" class="nav-link {{ $tab === 'settings' ? 'active' : '' }}">
                Settings
            </a>
        </li>
    </ul>

    <!-- TAB: SERVICES -->
    @if ($tab === 'services')
        <div>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h4 fw-bold">Your services</h2>
                <button 
                    wire:click="openServiceModal"
                    class="btn btn-primary"
                >
                    + Add service
                </button>
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
                                <span>⏳ Buffer: {{ $service->buffer }} min</span>
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
        </div>
    @endif

    <!-- TAB: RESERVATIONS -->
    @if ($tab === 'reservations')
        <div>
            <h2 class="h4 fw-bold mb-4">Reservations</h2>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Client</th>
                            <th>Service</th>
                            <th>Date & Time</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reservations as $reservation)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $reservation->client_name }}</div>
                                    <div class="small text-muted">{{ $reservation->client_email }}</div>
                                </td>
                                <td>{{ $reservation->service->name }}</td>
                                <td>
                                    {{ $reservation->start_time->format('d.m.Y H:i') }}<br>
                                    <span class="small text-muted">{{ $reservation->service->duration }} min</span>
                                </td>
                                <td>
                                    <span class="badge rounded-pill {{ 
                                        $reservation->status === 'confirmed' ? 'bg-success' :
                                        ($reservation->status === 'cancelled' ? 'bg-danger' : 'bg-warning text-dark')
                                    }}">
                                        {{ ucfirst($reservation->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        @if ($reservation->status === 'pending')
                                            <button 
                                                wire:click="confirmReservation({{ $reservation->id }})"
                                                class="btn btn-sm btn-link text-success text-decoration-none p-0"
                                            >
                                                Confirm
                                            </button>
                                        @endif
                                        @if ($reservation->status !== 'cancelled')
                                            <button 
                                                wire:click="cancelReservation({{ $reservation->id }})"
                                                class="btn btn-sm btn-link text-danger text-decoration-none p-0"
                                            >
                                                Cancel
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    No reservations
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $reservations->links() }}
            </div>
        </div>
    @endif

    <!-- TAB: SETTINGS -->
    @if ($tab === 'settings')
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="h4 fw-bold mb-4">Business settings</h2>
                <p class="text-muted">Business hours and other settings will be available here.</p>
            </div>
        </div>
    @endif

    <!-- MODAL: Edit service -->
    @if ($showServiceModal)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ $editingService ? 'Edit service' : 'New service' }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeServiceModal"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit="saveService">
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" wire:model="serviceName" class="form-control">
                                @error('serviceName')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea wire:model="serviceDescription" rows="3" class="form-control"></textarea>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <label class="form-label">Duration (min)</label>
                                    <input type="number" wire:model="serviceDuration" min="15" max="480" class="form-control">
                                    @error('serviceDuration')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-6">
                                    <label class="form-label">Buffer (min)</label>
                                    <input type="number" wire:model="serviceBuffer" min="0" max="120" class="form-control">
                                    @error('serviceBuffer')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Price (PLN)</label>
                                <input type="number" wire:model="servicePrice" step="0.01" min="0" class="form-control">
                            </div>

                            <div class="d-flex gap-2 mt-4">
                                <button 
                                    type="submit"
                                    class="btn btn-primary flex-grow-1"
                                >
                                    Save
                                </button>
                                <button 
                                    type="button"
                                    wire:click="closeServiceModal"
                                    class="btn btn-secondary flex-grow-1"
                                >
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
