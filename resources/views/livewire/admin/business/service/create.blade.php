<div>
@if ($open)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ $editingService ? 'Edit service' : 'New service' }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeServiceModal"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit="$dispatch('saveService')">
                            <div class="mb-3">
                                <label class="form-label">Nazwa</label>
                                <input type="text" wire:model="name" class="form-control">
                                @error('name')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Opis</label>
                                <textarea wire:model="description" rows="3" class="form-control"></textarea>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <label class="form-label">Czas trwania (min)</label>
                                    <input type="number" wire:model="duration" min="15" max="480" class="form-control">
                                    @error('duration')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-6">
                                    <label class="form-label">Przerwa (min)</label>
                                    <input type="number" wire:model="buffer" min="0" max="120" class="form-control">
                                    @error('buffer')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Cena (PLN)</label>
                                <input type="number" wire:model="price" step="0.01" min="0" class="form-control">
                            </div>

                            <div class="d-flex gap-2 mt-4">
                                <button 
                                    type="submit"
                                    class="btn btn-primary flex-grow-1"
                                >
                                    Save
                                </button>
                                <button 
                                    type="button"
                                    wire:click="closeServiceModal"
                                    class="btn btn-secondary flex-grow-1"
                                >
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
    </div>