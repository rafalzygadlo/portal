<div class="container mt-4">
    <div class="row">
        <div class="col-lg-6 offset-lg-3">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0"><i class="bi bi-plus-lg me-2"></i> Create Company</h5>
                </div>
                <div class="card-body p-4">
                    <form wire:submit.prevent="save">
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Company Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" wire:model.live="name" placeholder="Your company name">
                            @error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="subdomain" class="form-label fw-semibold">Subdomain</label>
                            <div class="input-group">
                                <input type="text" class="form-control @error('subdomain') is-invalid @enderror" id="subdomain" wire:model.defer="subdomain" placeholder="your-company">
                                <span class="input-group-text">.{{ config('app.company_domain') }}</span>
                            </div>
                            <small class="form-text text-muted">Letters, numbers, hyphens and underscores only</small>
                            @error('subdomain') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex justify-content-between gap-2 mt-4">
                            <a href="{{ route('user.profile') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <span wire:loading.remove><i class="bi bi-check-lg me-2"></i> Create</span>
                                <span wire:loading><span class="spinner-border spinner-border-sm me-2" role="status"></span> Creating...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
