<div class="container-fluid px-4 py-4">
    <div class="row">
        <div class="col-12">
            
            <div class="card border-0 shadow-sm">
                <!-- Nagłówek na pełną szerokość -->
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 fw-bold">
                        <i class="bi {{ $isEdit ? 'bi-pencil' : 'bi-plus-lg' }} me-2 text-primary"></i> 
                        {{ $isEdit ? 'Edit Offer' : 'Create Offer' }}
                    </h4>
                    <span class="text-muted small">Wszystkie pola oznaczone * są wymagane</span>
                </div>

                @if (session()->has('status'))
                    <div class="alert alert-success m-4 mb-0">
                        {{ session('status') }}
                    </div>
                @endif
                
                <div class="card-body p-4 p-lg-5">
                    @php
                        $existingCount = $isEdit ? count($existingPhotos ?? []) : 0;
                    @endphp

                    <form wire:submit.prevent="save">
                        {{-- Błędy walidacji --}}
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

                        <!-- SEKCJA 1: KATEGORIE NA PEŁNĄ SZEROKOŚĆ (Idealne dla rozbudowanych drzew) -->
                        <div class="card bg-light border-0 mb-4">
                            <div class="card-body">
                                <label class="form-label fw-bold text-dark mb-2">
                                    <i class="bi bi-grid me-1"></i> Wybierz kategorię <span class="text-danger">*</span>
                                </label>
                                <p class="text-muted small mb-3">Wybierz odpowiednią kategorię dla swojego ogłoszenia, aby użytkownicy łatwiej je znaleźli.</p>
                                
                                <livewire:category-selector wire:model.defer="categories" wire:key="category-selector-{{ $isEdit ? 'edit' : 'create' }}" />
                                @error('categories') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <!-- SEKCJA 2: TREŚĆ OGŁOSZENIA (Grid 2-kolumnowy dla optymalizacji szerokości) -->
                        <div class="row g-4 mb-4">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="title" class="form-label fw-semibold">{{ __('offers.title') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-lg @error('title') is-invalid @enderror" id="title" wire:model.live="title" placeholder="Wpisz chwytliwy tytuł...">
                                    @error('title') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            
                            <!-- Tutaj możesz dodać np. cenę, stan lub inne pole jednowierszowe, jeśli posiadasz -->
                        </div>

                        <div class="mb-4">
                            <label for="content" class="form-label fw-semibold">{{ __('offers.content') }} <span class="text-danger">*</span></label>
                            <textarea class="form-control form-control-lg @error('content') is-invalid @enderror" id="content" rows="8" wire:model.live="content" placeholder="Opisz szczegółowo swój przedmiot lub usługę..."></textarea>
                            @error('content') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <!-- SEKCJA 3: ZDJĘCIA -->
                        <div class="card bg-light border-0 mb-4">
                            <div class="card-body">
                                <label class="form-label fw-bold text-dark mb-2">
                                    <i class="bi bi-images me-1"></i> {{ __('offers.photos') }}
                                </label>
                                <p class="text-muted small mb-3">Dodaj wyraźne zdjęcia, aby zwiększyć zainteresowanie ogłoszeniem.</p>
                                
                                <livewire:gallery.uploader wire:model.live="allPhotos" inputId="offer-photos" :existingPhotos="$existingPhotos" :errorFields="['allPhotos', 'allPhotos.*']" :key="'offer-upload-' . ($isEdit ? 'edit' : 'create')" />
                            </div>
                        </div>

                        <!-- PRZYCISKI AKCJI -->
                        <div class="d-flex justify-content-between align-items-center border-top pt-4 mt-4">
                            <a href="{{ route('user.profile') }}" class="btn btn-outline-secondary px-4">
                                <i class="bi bi-arrow-left me-2"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary px-5">
                                <span wire:loading.remove><i class="bi bi-save me-2"></i> {{ __('global.save') }}</span>
                                <span wire:loading><span class="spinner-border spinner-border-sm me-2" role="status"></span> {{ __('global.save') }}</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>