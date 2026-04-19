<div class="business-dashboard container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Resources</h1>
          <button  wire:click="$dispatch('openResourceModal')" class="btn btn-primary">+ Add</button>
    </div>

    <div class="table-responsive border rounded shadow-sm bg-white">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Active</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($resources as $resource)
                    <tr>
                        <td class="fw-semibold">{{ $resource->name }}</td>
                        <td>{{ $resource->type }}</td>
                        <td>
                            <span class="badge {{ $resource->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $resource->is_active ? 'Yes' : 'No' }}
                            </span>
                        </td>
                        <td class="text-end">
                            <button
                                wire:click="$dispatch('openResourceModal', [{{ $resource->id }}])"
                                class="btn btn-sm btn-outline-primary"
                            >
                                Manage
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">
                            No resources found. Click + Add to create one.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <livewire:admin.business.resource.create />
</div>
