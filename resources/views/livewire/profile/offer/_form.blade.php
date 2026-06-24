@php
    $existingCount = $isEdit ? count($existingPhotos ?? []) : 0;
@endphp

<form wire:submit.prevent="save">
    <div class="mb-3">
        <label for="title" class="form-label fw-semibold">{{ __('offers.title') }}</label>
        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" wire:model.defer="title" placeholder="Offer title">
        @error('title') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">Category</label>
        <livewire:category-selector wire:model.defer="category_id" wire:key="category-selector-{{ $isEdit ? 'edit' : 'create' }}" />
        @error('category_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label for="content" class="form-label fw-semibold">{{ __('offers.content') }}</label>
        <textarea class="form-control @error('content') is-invalid @enderror" id="content" rows="6" wire:model.defer="content" placeholder="Describe your offer..."></textarea>
        @error('content') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">{{ __('offers.photos') }}</label>


        <div wire:loading wire:target="photos" class="text-primary small mb-2">
            <div class="spinner-border spinner-border-sm me-2" role="status"></div>Przesyłanie zdjęć...
        </div>

        <livewire:upload.gallery
            wire:model="photos"
            :existingPhotos="is_array($existingPhotos) ? $existingPhotos : ($existingPhotos?->toArray() ?? [])"
            inputId="offer-photos"
            field="photos"
            :maxPhotos="$maxPhotos"
            :title="$isEdit ? 'Edit photos' : 'Offer photos'"
            :showReorder="true"
            :errorFields="['photos', 'photos.*']"
            :key="'offer-upload-' . ($isEdit ? 'edit' : 'create')"
        />
    </div>

    <div class="d-flex justify-content-between gap-2 mt-4">
        <a href="{{ route('user.profile') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i> Back
        </a>
        <button type="submit" class="btn btn-primary">
            <span wire:loading.remove><i class="bi {{ $isEdit ? 'bi-save' : 'bi-send' }} me-2"></i> {{ $isEdit ? 'Save changes' : __('offers.add_offer') }}</span>
            <span wire:loading><span class="spinner-border spinner-border-sm me-2" role="status"></span> {{ __('offers.saving') }}</span>
        </button>
    </div>
</form>
