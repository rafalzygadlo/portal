<div>
    <h1 class="h3 mb-4">Add new resource</h1>

    <div class="card">
        <div class="card-body">
            <form wire:submit.prevent="save">
                <div class="mb-3">
                    <label for="name" class="form-label">Nazwa zasobu</label>
                    <input type="text" id="name" wire:model="name" class="form-control">
                    @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label for="type" class="form-label">Typ zasobu</label>
                    <select id="type" wire:model.live="type" class="form-select">
                        <option value="person">Osoba</option>
                        <option value="facility">Miejsce / Pomieszczenie</option>
                        <option value="equipment">Equipment</option>
                    </select>
                    @error('type') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                @if ($type === 'person')
                    <div class="mb-3">
                        <label for="userId" class="form-label">Link to user (optional)</label>
                        <select id="userId" wire:model="userId" class="form-select">
                            <option value="">-- Select user --</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                        @error('userId') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                @endif


                <div class="d-flex justify-content-end">
                    <a href="{{ route('business.resources.index', $business) }}" class="btn btn-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
