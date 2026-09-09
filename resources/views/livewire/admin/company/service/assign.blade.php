<div>
    @if ($open)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Assign employees to service</h5>
                        <button type="button" class="btn-close" wire:click="close" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted small mb-3">Select which employees can perform this service.</p>
                        @forelse ($people as $person)
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    value="{{ $person->id }}"
                                    wire:model="userIds"
                                    id="assign-person-{{ $person->id }}"
                                >
                                <label class="form-check-label" for="assign-person-{{ $person->id }}">
                                    {{ $person->pivot->display_name ?: $person->name }}
                                </label>
                            </div>
                        @empty
                            <div class="form-text">Add an employee to the company first.</div>
                        @endforelse
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