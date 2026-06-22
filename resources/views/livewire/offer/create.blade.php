
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
            <input type="file" class="d-none @if($errors->has('photos') || $errors->has('photos.*')) is-invalid @endif" id="offer-photos-public" wire:model="photos" multiple accept="image/*" @if(count($photos) >= \App\Livewire\Offer\Create::MAX_PHOTOS) disabled @endif>
            <div wire:loading wire:target="photos" class="text-primary small mb-2">
                <div class="spinner-border spinner-border-sm me-2" role="status"></div>Przesyłanie zdjęć...
            </div>
            <livewire:upload.gallery
                wire:model="photos"
                inputId="offer-photos-public"
                field="photos"
                :maxPhotos="\App\Livewire\Offer\Create::MAX_PHOTOS"
                title="Zdjęcia oferty"
                :subtitle="'Dodaj do ' . \App\Livewire\Offer\Create::MAX_PHOTOS . ' zdjęć, aby oferta była bardziej widoczna.'"
                :showReorder="true"
                :errorFields="['photos', 'photos.*']"
                :key="'offer-upload-public'"
            />
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