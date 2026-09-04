<div class="col py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Resources</h1>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.company.dashboard', ['company' => $company]) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2" aria-hidden="true"></i>Back
            </a>
            <button wire:click="$dispatch('open',[])" class="btn btn-primary">+ Add</button>
        </div>
    </div>

    @forelse ($resourcesGrouped as $type => $resources)
        @php
            $typeLabel = match ($type) {
                'person' => 'People',
                'facility' => 'Facilities',
                'equipment' => 'Equipment',
                default => ucfirst($type),
            };
            $typeIcon = match ($type) {
                'person' => 'bi-person',
                'facility' => 'bi-building',
                'equipment' => 'bi-tools',
                default => 'bi-box',
            };
        @endphp

        <div class="card shadow-sm border mb-4">
            <div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
                <h2 class="h5 mb-0 fw-bold d-flex align-items-center gap-2 text-capitalize">
                    <i class="bi {{ $typeIcon }} text-primary" aria-hidden="true"></i>
                    {{ $typeLabel }}
                </h2>
                <span class="badge bg-secondary rounded-pill">{{ $resources->count() }}</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Details</th>
                            <th>Active</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($resources as $resource)
                            <tr>
                                <td class="fw-semibold">{{ $resource->name }}</td>
                                <td>
                                    @if ($resource->type === 'person' && $resource->assignedUser)
                                        <span class="text-muted small">
                                            <i class="bi bi-person-badge me-1"></i>{{ $resource->assignedUser->name }}
                                        </span>
                                    @elseif ($resource->type === 'equipment' && $resource->hourly_rate !== null)
                                        <span class="text-muted small">
                                            <i class="bi bi-tag me-1"></i>{{ number_format($resource->hourly_rate, 2) }} PLN/h
                                        </span>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $resource->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $resource->is_active ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <button wire:click="$dispatch('open', [{{ $resource->id }}])" class="btn btn-sm btn-outline-secondary me-1">
                                        Edit
                                    </button>
                                    @if (!$resource->is_active)
                                        <span class="text-muted small">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="card shadow-sm border mb-4">
            <div class="card-body text-center text-muted py-5">
                <i class="bi bi-box-seam display-4 d-block mb-3 text-secondary"></i>
                <p class="mb-0">No resources found. Click + Add to create one.</p>
            </div>
        </div>
    @endforelse

    <livewire:admin.company.resource.create :company="$company"/> 
</div>
