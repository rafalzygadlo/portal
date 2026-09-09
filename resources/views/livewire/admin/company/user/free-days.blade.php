<div>
    @if ($open)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Free days</h5>
                        <button type="button" class="btn-close" wire:click="close" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Time off periods</label>
                            @forelse ($unavailablePeriods as $index => $period)
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="small">{{ $period['start'] }} to {{ $period['end'] }}</span>
                                    <button type="button" wire:click="removePeriod({{ $index }})" class="btn btn-sm btn-outline-danger">Remove</button>
                                </div>
                            @empty
                                <div class="form-text">No free days set yet.</div>
                            @endforelse
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Add new free day period</label>
                            <div class="row g-2">
                                <div class="col-5">
                                    <input type="date" wire:model="timeOffStart" class="form-control form-control-sm">
                                </div>
                                <div class="col-5">
                                    <input type="date" wire:model="timeOffEnd" class="form-control form-control-sm">
                                </div>
                                <div class="col-2">
                                    <span class="form-text">Add on save</span>
                                </div>
                            </div>
                            @error('timeOffEnd') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="close">Cancel</button>
                        <button type="button" class="btn btn-primary" wire:click="save">Save</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>