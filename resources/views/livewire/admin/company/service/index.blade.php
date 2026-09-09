<div class="col py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Services</h1>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.company.dashboard', ['company' => $company]) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2" aria-hidden="true"></i>Back
            </a>
            <div class="btn-group btn-group-sm" role="group">
                <button
                    type="button"
                    class="btn btn-sm {{ ! $showDeleted ? 'btn-primary' : 'btn-outline-secondary' }}"
                    wire:click="$set('showDeleted', false)"
                >
                    Active
                </button>
                <button
                    type="button"
                    class="btn btn-sm {{ $showDeleted ? 'btn-primary' : 'btn-outline-secondary' }}"
                    wire:click="$set('showDeleted', true)"
                >
                    Deleted
                </button>
            </div>

            @unless ($showDeleted)
                <button wire:click="$dispatch('open', [])" class="btn btn-sm btn-primary">+ Add</button>
            @endunless
        </div>
    </div>

    <div class="table-responsive border shadow bg-white">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th scope="col">Service</th>
                    <th scope="col">Description</th>
                    <th scope="col">Duration</th>
                    <th scope="col">Price</th>
                    <th scope="col">Break</th>
                    <th scope="col">Assigned</th>
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
                            @php($assignedPeople = $service->companyUsers()->with('user')->get())
                            @if ($assignedPeople->isNotEmpty())
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach ($assignedPeople as $assignedPerson)
                                        <span class="badge bg-primary-subtle text-primary">{{ $assignedPerson->display_name }}</span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-muted small">No one assigned</span>
                            @endif
                        </td>
                        <td>
                            @if ($service->trashed())
                                <span class="badge bg-danger">Deleted</span>
                            @else
                                <span class="badge {{ $service->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $service->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="dropdown">
                                <button
                                    class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                    type="button"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false"
                                >
                                    Actions
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    @if ($service->trashed())
                                        <li>
                                            <button
                                                type="button"
                                                class="dropdown-item text-success"
                                                wire:click="restore({{ $service->id }})"
                                            >
                                                Restore
                                            </button>
                                        </li>
                                        <li>
                                            <button
                                                type="button"
                                                class="dropdown-item text-danger"
                                                wire:click="forceDelete({{ $service->id }})"
                                                wire:confirm="Permanently delete this service?"
                                            >
                                                Delete permanently
                                            </button>
                                        </li>
                                    @else
                                        <li>
                                            <button
                                                type="button"
                                                class="dropdown-item"
                                                wire:click="toggleActive({{ $service->id }})"
                                            >
                                                {{ $service->is_active ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </li>
                                        <li>
                                            <button
                                                type="button"
                                                class="dropdown-item"
                                                wire:click="$dispatch('open', [{{ $service->id }}])"
                                            >
                                                Edit
                                            </button>
                                        </li>
                                        <li>
                                            <button
                                                type="button"
                                                class="dropdown-item"
                                                wire:click="$dispatch('openAssign', [{{ $service->id }}])"
                                            >
                                                Assign employees
                                            </button>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <button
                                                type="button"
                                                class="dropdown-item text-danger"
                                                wire:click="delete({{ $service->id }})"
                                                wire:confirm="Are you sure you want to delete this service?"
                                            >
                                                Delete
                                            </button>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            @if ($showDeleted)
                                No deleted services found.
                            @else
                                No services found yet. Click + Add to create your first service.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <livewire:admin.company.service.create :company="$company" />
    <livewire:admin.company.service.assign :company="$company" />
</div>
