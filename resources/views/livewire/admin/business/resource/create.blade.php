<div>
    @if ($open)
        <div class="modal-backdrop fade show"></div>
        <div class="modal d-block" tabindex="-1" role="dialog" style="background: rgba(0, 0, 0, 0.45);">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content shadow-lg">
                    <div class="modal-header border-0">
                        <div>
                            <h5 class="modal-title">{{ $editingResource ? 'Edit resource' : 'Add new resource' }}</h5>
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

                            @if ($type === 'equipment')
                                <div class="mb-3">
                                    <label for="hourly-rate" class="form-label">Hourly rental price (PLN)</label>
                                    <input id="hourly-rate" type="number" min="0" step="0.01" wire:model="hourlyRate" class="form-control">
                                    @error('hourlyRate') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                            @endif

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

                            <div class="mb-3">
                                <label class="form-label">Resource working hours</label>
                                @foreach ($workingHours as $day => $hours)
                                    <div class="row g-2 align-items-center mb-2">
                                        <div class="col-3 text-capitalize small">{{ $day }}</div>
                                        <div class="col-4">
                                            <input type="time" wire:model="workingHours.{{ $day }}.open" class="form-control form-control-sm" @disabled($hours['closed'] ?? false)>
                                        </div>
                                        <div class="col-4">
                                            <input type="time" wire:model="workingHours.{{ $day }}.close" class="form-control form-control-sm" @disabled($hours['closed'] ?? false)>
                                        </div>
                                        <div class="col-1">
                                            <input type="checkbox" wire:model="workingHours.{{ $day }}.closed" class="form-check-input" title="Closed">
                                        </div>
                                    </div>
                                @endforeach
                                <div class="form-text">Leave the default schedule or set different hours for this resource.</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Time off</label>
                                @foreach ($unavailablePeriods as $index => $period)
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <span class="small">{{ $period['start'] }} to {{ $period['end'] }}</span>
                                        <button type="button" wire:click="removeUnavailablePeriod({{ $index }})" class="btn btn-sm btn-outline-danger">Remove</button>
                                    </div>
                                @endforeach
                                <div class="row g-2">
                                    <div class="col-5"><input type="date" wire:model="timeOffStart" class="form-control form-control-sm"></div>
                                    <div class="col-5"><input type="date" wire:model="timeOffEnd" class="form-control form-control-sm"></div>
                                    <div class="col-2"><span class="form-text">Add on save</span></div>
                                </div>
                                @error('timeOffEnd') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-secondary" wire:click="$set('open', false)">Cancel</button>
                                <button type="submit" class="btn btn-primary">{{ $editingResource ? 'Update' : 'Save' }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
