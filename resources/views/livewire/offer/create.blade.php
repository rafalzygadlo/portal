<div>
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
            <label for="photos" class="form-label">{{ __('offers.photos') }}</label>
            <input type="file" class="form-control @if($errors->has('photos') || $errors->has('photos.*')) is-invalid @endif" id="photos" wire:model.defer="photos" multiple accept="image/*">
            <div wire:loading wire:target="photos" class="text-primary small mt-1">
                <div class="spinner-border spinner-border-sm" role="status"></div> {{ __('offers.uploading_previews') }}
            </div>
            @error('photos') <div class="invalid-feedback">{{ $message }}</div> @enderror
            @error('photos.*') <div class="invalid-feedback">{{ $message }}</div> @enderror
            
            @if ($photos)
                <div class="d-flex flex-wrap gap-2 mt-3">
                    @foreach ($photos as $index => $photo)
                        <div class="position-relative">
                            <img loading="lazy" src="{{ $photo->temporaryUrl() }}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                            <button type="button" 
                                    class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1" 
                                    style="padding: 0px 5px; line-height: 1.2;" 
                                    wire:click="removePhoto({{ $index }})">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Przyciski akcji na dole formularza -->
        <div class="d-flex justify-content-end gap-2 mt-4">
            <!-- Zamiast closeOfferModal strzelasz globalnym zdarzeniem zamknięcia modalu -->
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