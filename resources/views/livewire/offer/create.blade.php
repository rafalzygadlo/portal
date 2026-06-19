
<div class="container mt-4">
    <h1 class="mb-4">{{ __('offers.create') }}</h1>
    <form wire:submit.prevent="save">
        <div class="mb-3">
            <label for="title" class="form-label">{{ __('offers.title') }}</label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" wire:model.defer="title">
            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <livewire:category-selector wire:model.defer="category_id" wire:key="category-selector" />
            @error('category_id') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="content" class="form-label">{{ __('offers.content') }}</label>
            <textarea class="form-control @error('content') is-invalid @enderror" id="content" rows="5" wire:model.defer="content"></textarea>
            @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">{{ __('offers.photos') }}</label>
            <div class="border rounded-3 p-3 bg-light">
                <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-3 mb-3">
                    <div>
                        <h6 class="mb-1">Zdjęcia oferty</h6>
                        <div class="text-muted small">Dodaj do {{ \App\Livewire\Offer\Create::MAX_PHOTOS }} zdjęć, aby oferta była bardziej widoczna.</div>
                    </div>
                    <div class="text-end">
                        <div class="badge bg-primary text-white">{{ count($photos) }} / {{ \App\Livewire\Offer\Create::MAX_PHOTOS }}</div>
                    </div>
                </div>
                <label for="photos" class="file-drop-zone d-flex align-items-center justify-content-between gap-3 p-3 rounded-3 mb-3 @if(count($photos) >= \App\Livewire\Offer\Create::MAX_PHOTOS) disabled @endif" @if(count($photos) >= \App\Livewire\Offer\Create::MAX_PHOTOS) style="pointer-events: none; opacity: .7;" @endif>
                    <div class="d-flex align-items-center gap-2">
                        <span class="fs-4 text-primary"><i class="bi bi-cloud-upload"></i></span>
                        <div>
                            <div class="fw-semibold">Wybierz zdjęcia</div>
                            <div class="small text-muted">JPG, PNG, max 8 MB na plik.</div>
                        </div>
                    </div>
                    <div class="small text-muted text-nowrap">
                        @if(count($photos) >= \App\Livewire\Offer\Create::MAX_PHOTOS)
                            Limit zdjęć osiągnięty
                        @else
                            Kliknij, aby wybrać pliki
                        @endif
                    </div>
                </label>
                <input type="file" class="d-none @if($errors->has('photos') || $errors->has('photos.*')) is-invalid @endif" id="photos" wire:model="newPhotos" multiple accept="image/*" @if(count($photos) >= \App\Livewire\Offer\Create::MAX_PHOTOS) disabled @endif>
                <div class="form-text mb-2">Możesz dodać maksymalnie {{ \App\Livewire\Offer\Create::MAX_PHOTOS }} zdjęć.</div>
                <div wire:loading wire:target="newPhotos" class="text-primary small mb-2">
                    <div class="spinner-border spinner-border-sm me-2" role="status"></div>Przesyłanie zdjęć...
                </div>
                @if($errors->has('photos') || $errors->has('photos.*'))
                    <div class="invalid-feedback d-block mb-2">
                        @error('photos') {{ $message }} @enderror
                        @error('photos.*') {{ $message }} @enderror
                    </div>
                @endif
                @if ($photos)
                    <div class="mt-2 small text-muted mb-3">Wybrano {{ count($photos) }} plik(ów). Przestaw kolejność zdjęć, jeśli chcesz zmienić ich układ.</div>
                    <div id="offer-photos-preview" class="row g-2">
                        @foreach ($photos as $index => $photo)
                            <div class="col-6 col-sm-4 col-md-2">
                                <div class="file-preview-card rounded-3 bg-white border position-relative overflow-hidden h-100" data-photo-index="{{ $index }}" wire:key="photo-{{ $index }}">
                                    <div class="d-flex justify-content-between align-items-center px-2 py-2 border-bottom bg-light">
                                        <span class="badge bg-secondary file-preview-badge">#{{ $index + 1 }}</span>
                                        <button type="button" class="btn btn-sm btn-danger" wire:click="removePhoto({{ $index }})" aria-label="Usuń zdjęcie {{ $index + 1 }}">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                                    <img loading="lazy" src="{{ $photo->temporaryUrl() }}" class="img-fluid w-100" style="height: 90px; object-fit: cover;">
                                    <div class="px-2 py-2">
                                        <div class="d-flex gap-1">
                                            <button type="button" class="btn btn-outline-primary btn-sm flex-fill" wire:click="movePhotoUp({{ $index }})" @if($index === 0) disabled @endif aria-label="Przenieś zdjęcie w górę">
                                                <i class="bi bi-arrow-up"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-primary btn-sm flex-fill" wire:click="movePhotoDown({{ $index }})" @if($index === count($photos) - 1) disabled @endif aria-label="Przenieś zdjęcie w dół">
                                                <i class="bi bi-arrow-down"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Przyciski akcji na dole formularza -->
        <div class="d-flex justify-content-end gap-2 mt-4">
           
            <button type="button" class="btn btn-secondary" wire:click="$dispatch('closeModal')">
                {{ __('offers.cancel') }}
            </button>
            <button type="submit" class="btn btn-primary">
                <span wire:loading.remove><i class="bi bi-send"></i> {{ __('offers.add_offer') }}</span>
                <span wire:loading>{{ __('offers.saving') }}</span>
            </button>
        </div>
    </form>
</div>