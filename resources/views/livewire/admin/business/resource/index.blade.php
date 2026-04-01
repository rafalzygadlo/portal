<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Zasoby</h1>
          <button 
                    wire:click="openResourceModal"
                    class="btn btn-primary"
                >
                    + Dodaj zasob
                </button>
        
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nazwa</th>
                        <th>Typ</th>
                        <th>Aktywny</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($resources as $resource)
                        <tr>
                            <td>{{ $resource->name }}</td>
                            <td>{{ $resource->type }}</td>
                            <td>
                                @if ($resource->is_active)
                                    <span class="badge bg-success">Tak</span>
                                @else
                                    <span class="badge bg-danger">Nie</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="#" class="btn btn-sm btn-secondary">Zarządzaj</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Brak zasobów.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
