<div class="container mt-4">
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <div class="card border-0 shadow-sm">
                   <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi {{ $isEdit ? 'bi-pencil' : 'bi-plus-lg' }} me-2"></i>
                        {{ $isEdit ? 'Edit' : 'Create' }} Business</h5>
                </div>
                
                <div class="card-body p-4">
                    <form wire:submit.prevent="save">
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Business Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" wire:model="name">
                            @error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                         <div class="mb-3">
                            <label for="subdomain" class="form-label fw-semibold">Subdomain</label>
                            <div class="input-group">
                                <input type="text" class="form-control @error('subdomain') is-invalid @enderror"
                                    id="subdomain" wire:model.defer="subdomain" placeholder="np. moja-firma">
                                <span class="input-group-text"> {{ config('app.business_domain') }}</span>
                                @error('subdomain')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text">Allowed: letters, numbers, hyphens, and underscores.</div>
                        </div>


                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" wire:model="description" rows="10"></textarea>
                            @error('description') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                       
                        <div class="d-flex justify-content-between gap-2 mt-4">
                            <a href="{{ route('user.profile') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i> Back
                            </a>
                            <button type="submit" wire:loading.attr="disabled" class="btn btn-primary">
                                <span ><i class="bi bi-save me-2"></i> Save</span>
                                <span wire:loading wire:target="update"><span class="spinner-border spinner-border-sm me-2" role="status"></span> Saving...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
