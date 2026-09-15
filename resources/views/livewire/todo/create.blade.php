<div class="container-fluid px-4 py-4">
    <div class="row">
        <div class="col-12">
            
            <div class="card border-0 shadow-sm">
                <!-- Nagłówek karty -->
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 fw-bold">
                        <i class="bi bi-pencil-square me-2 text-primary"></i> Edycja elementu
                    </h4>
                    <span class="text-muted small">Wszystkie pola oznaczone * są wymagane</span>
                </div>

                <div class="card-body p-4 p-lg-5">
                    <form wire:submit.prevent="save">
                        
                        {{-- Błędy walidacji ogólne --}}
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

                        <!-- Tytuł -->
                        <div class="mb-4">
                            <label for="title" class="form-label fw-semibold">
                                {{ __('global.title') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control form-control-lg @error('title') is-invalid @enderror" 
                                   id="title" wire:model.defer="title" placeholder="Wpisz tytuł...">
                            @error('title') 
                                <div class="invalid-feedback d-block">{{ $message }}</div> 
                            @enderror
                        </div>

                        <!-- Opis -->
                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">
                                Opis <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" rows="6" wire:model.defer="description" 
                                      placeholder="Wpisz treść opisu..."></textarea>
                            @error('description') 
                                <div class="invalid-feedback d-block">{{ $message }}</div> 
                            @enderror
                        </div>

                        <!-- Przyciski akcji -->
                        <div class="d-flex justify-content-between align-items-center gap-2 border-top pt-4 mt-4">
                           <a href="{{ route('user.profile') }}" class="btn btn-outline-secondary px-4">
                                <i class="bi bi-arrow-left me-2"></i> Back
                            </a>
                            
                            <button type="submit" wire:loading.attr="disabled" class="btn btn-primary px-5">
                                <span wire:loading.remove wire:target="save">
                                    <i class="bi bi-save me-1"></i> {{ __('global.save') }}
                                </span>
                                <span wire:loading wire:target="save">
                                    <span class="spinner-border spinner-border-sm me-2" role="status"></span> Saving...
                                </span>
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>