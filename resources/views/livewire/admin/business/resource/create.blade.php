<div>
    @if ($open)
        <div class="modal-backdrop fade show"></div>
        <div class="modal d-block" tabindex="-1" role="dialog" style="background: rgba(0, 0, 0, 0.45);">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content shadow-lg">
                    <div class="modal-header border-0">
                        <div>
                            <h5 class="modal-title">Add new resource</h5>
                            <p class="text-muted mb-0">Add a resource for {{ $business->name }}.</p>
                        </div>
                        <button type="button" class="btn-close" aria-label="Close" wire:click="$set('open', false)"></button>
                    </div>

                    <div class="modal-body">
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

                            @if ($type === 'person12')
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

                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-secondary" wire:click="$set('open', false)">Cancel</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
