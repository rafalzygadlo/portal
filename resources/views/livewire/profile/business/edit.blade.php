<div class="container mt-4">
    <div class="row">
        <div class="col-lg-6 offset-lg-3">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-pencil me-2"></i> Edit Business</h5>
                    <button type="button" class="btn btn-sm btn-danger" wire:click="delete" onclick="confirm('Are you sure?') || event.stopImmediatePropagation()">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                </div>
                <div class="card-body p-4">
                    <form wire:submit.prevent="save">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Business Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" wire:model="name">
                            @error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" wire:model="description" rows="4"></textarea>
                            @error('description') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="alert alert-info small">
                            <i class="bi bi-info-circle me-2"></i>
                            Subdomain: <code>{{ $business->subdomain }}</code>.{{ config('app.business_domain') }}
                        </div>

                        <div class="d-flex justify-content-between gap-2 mt-4">
                            <a href="{{ route('user.profile') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <span wire:loading.remove><i class="bi bi-save me-2"></i> Save</span>
                                <span wire:loading><span class="spinner-border spinner-border-sm me-2" role="status"></span> Saving...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
