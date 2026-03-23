<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm">
                
                <div class="card-header">
                    <h1 class="h4 mb-0">Dodaj nową firmę</h1>
                </div>

                <div class="card-body p-4">
                    <form wire:submit.prevent="save">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nazwa firmy</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                wire:model.live="name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="subdomain" class="form-label">Subdomena</label>
                            <div class="input-group">
                                <input type="text" class="form-control @error('subdomain') is-invalid @enderror"
                                    id="subdomain" wire:model.defer="subdomain" placeholder="np. moja-firma">
                                <span class="input-group-text"> {{ config('app.business_domain'); }}</span>
                                @error('subdomain')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text">Dozwolone: litery, cyfry, myślnik i podkreślenie.</div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('business.index') }}" class="btn btn-outline-secondary me-2">Anuluj</a>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i>
                                <span wire:loading.remove>Dodaj firmę</span>
                                <span wire:loading>Zapisywanie...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>