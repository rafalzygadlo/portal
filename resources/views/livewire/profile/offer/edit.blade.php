<div class="container mt-4">
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-pencil me-2"></i> Edit Offer</h5>
                    <button type="button" class="btn btn-sm btn-danger" wire:click="delete" onclick="confirm('Are you sure?') || event.stopImmediatePropagation()">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                </div>
                <div class="card-body p-4">
                    @include('livewire.profile.offer._form', [
                        'isEdit' => true,
                        'maxPhotos' => \App\Livewire\Profile\Offer\Edit::MAX_PHOTOS,
                        'existingPhotos' => $existingPhotos,
                    ])
                </div>
            </div>
        </div>
    </div>
</div>
