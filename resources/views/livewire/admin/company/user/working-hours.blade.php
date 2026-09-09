<div>
    @if ($open)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Working hours</h5>
                        <button type="button" class="btn-close" wire:click="close" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Working hours</label>
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
                                        <input type="checkbox" wire:model="workingHours.{{ $day }}.closed" class="form-check-input" title="Day off">
                                    </div>
                                </div>
                            @endforeach
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