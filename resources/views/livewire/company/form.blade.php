<div class="container-fluid px-4 py-4">
    <div class="row">
        <div class="col-12">
            
            <div class="card border-0 shadow-sm">
                <!-- Nagłówek na pełną szerokość -->
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 fw-bold">
                        <i class="bi {{ $isEdit ? 'bi-pencil' : 'bi-plus-lg' }} me-2 text-primary"></i> 
                        {{ $isEdit ? 'Edit Company' : 'Create Company' }}
                    </h4>
                    <span class="text-muted small">Wszystkie pola oznaczone * są wymagane</span>
                </div>

                <div class="card-body p-4 p-lg-5">
                    <form wire:submit.prevent="save">
                        
                        {{-- Błędy walidacji ogólne, jeśli potrzebne --}}
                        @if ($errors->any())
                            <div class="alert alert-danger mb-4">
                                <h5 class="alert-heading">There were errors!</h5>
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- SEKCJA 1: KATEGORIE NA PEŁNĄ SZEROKOŚĆ -->
                        <div class="card bg-light border-0 mb-4">
                            <div class="card-body">
                                <label class="form-label fw-bold text-dark mb-2">
                                    <i class="bi bi-grid me-1"></i> Wybierz kategorię <span class="text-danger">*</span>
                                </label>
                                <p class="text-muted small mb-3">Wybierz kategorię branżową dla swojej firmy, aby klienci mogli Cię łatwiej znaleźć.</p>
                                
                                <livewire:category-selector wire:model.defer="categories" wire:key="category-selector-company-{{ $isEdit ? 'edit' : 'create' }}" />
                                @error('categories') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <!-- SEKCJA 2: NAZWA ORAZ PODDOMENA (Grid 2-kolumnowy) -->
                        <div class="row g-4 mb-4">
                            <!-- Nazwa firmy -->
                            <div class="col-lg-6">
                                <label class="form-label fw-semibold">Company Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" wire:model.defer="name" placeholder="np. Moja Firma Sp. z o.o.">
                                @error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>

                            <!-- Poddomena -->
                            <div class="col-lg-6">
                                <label for="subdomain" class="form-label fw-semibold">Subdomain <span class="text-danger">*</span></label>
                                <div class="input-group input-group-lg">
                                    <input type="text" class="form-control @error('subdomain') is-invalid @enderror"
                                        id="subdomain" wire:model.defer="subdomain" placeholder="moja-firma">
                                    <span class="input-group-text bg-light text-muted">.{{ config('app.company_domain') }}</span>
                                </div>
                                <div class="form-text">Dozwolone: litery, cyfry, myślniki i podkreślenia.</div>
                                @error('subdomain')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- SEKCJA 3: OPIS -->
                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control form-control-lg @error('description') is-invalid @enderror" id="description" wire:model.defer="description" rows="8" placeholder="Napisz czym zajmuje się Twoja firma..."></textarea>
                            @error('description') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <!-- PRZYCISKI AKCJI -->
                        <div class="d-flex justify-content-between align-items-center border-top pt-4 mt-4">
                            <a href="{{ route('user.profile') }}" class="btn btn-outline-secondary px-4">
                                <i class="bi bi-arrow-left me-2"></i> Back
                            </a>
                            <button type="submit" wire:loading.attr="disabled" class="btn btn-primary px-5">
                                <span wire:loading.remove wire:target="save"><i class="bi bi-save me-2"></i> Save</span>
                                <span wire:loading wire:target="save"><span class="spinner-border spinner-border-sm me-2" role="status"></span> Saving...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>