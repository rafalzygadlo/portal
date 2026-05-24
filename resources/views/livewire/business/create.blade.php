                <div>
                    <form wire:submit.prevent="save">
                        <div class="mb-3">
                            <label for="name" class="form-label">Business name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                wire:model.live="name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="subdomain" class="form-label">Subdomain</label>
                            <div class="input-group">
                                <input type="text" class="form-control @error('subdomain') is-invalid @enderror"
                                    id="subdomain" wire:model.defer="subdomain" placeholder="np. moja-firma">
                                <span class="input-group-text"> {{ config('app.business_domain'); }}</span>
                                @error('subdomain')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text">Allowed: letters, numbers, hyphens, and underscores.</div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('business.index') }}" class="btn btn-outline-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i>
                                <span wire:loading.remove>Add business</span>
                                <span wire:loading>Saving...</span>
                            </button>
                        </div>
                    </form>
                </div>
            