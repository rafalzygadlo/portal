<div class="business-dashboard container py-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Services</h1>
          <button wire:click="$dispatch('openServiceModal', [])" class="btn btn-primary">+ Add</button>
    </div>

        <div class="table-responsive border shadow-sm bg-white">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Service</th>
                        <th scope="col">Description</th>
                        <th scope="col">Duration</th>
                        <th scope="col">Price</th>
                        <th scope="col">Break</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($services as $service)
                        <tr>
                            <td class="fw-semibold">{{ $service->name }}</td>
                            <td class="text-muted small">{{ \Illuminate\Support\Str::limit($service->description, 80) }}</td>
                            <td>{{ $service->duration }} min</td>
                            <td>{{ $service->price ? number_format($service->price, 2) . ' PLN' : '-' }}</td>
                            <td>{{ $service->buffer }} min</td>
                            <td>
                                <span class="badge {{ $service->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $service->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <button
                                    wire:click="toggleServiceActive({{ $service->id }})"
                                    class="btn btn-sm {{ $service->is_active ? 'btn-success' : 'btn-outline-secondary' }} me-1"
                                >
                                    {{ $service->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                                <button
                                    wire:click="$dispatch('openServiceModal', [{{ $service->id }}])"
                                    class="btn btn-sm btn-outline-primary me-1"
                                >
                                    Edit
                                </button>
                                <button
                                    wire:click="deleteService({{ $service->id }})"
                                    class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Are you sure you want to delete this service?')"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                No services found yet. Click + Add to create your first service.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <livewire:admin.business.service.create />
        </div>
    
        