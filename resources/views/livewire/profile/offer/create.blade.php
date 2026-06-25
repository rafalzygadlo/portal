<div class="container mt-4">
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0"><i class="bi bi-plus-lg me-2"></i> Create Offer</h5>
                </div>

                <div class="card-body p-4">
                    @include('livewire.profile.offer._form', [
                        'isEdit' => false,
                         'existingPhotos' => $allPhotos])
                </div>
            </div>
        </div>
    </div>
</div>