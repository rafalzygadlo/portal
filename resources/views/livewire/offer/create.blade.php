<div>

    @if ($open)
        <div class="modal-backdrop fade show"></div>
        <div class="modal d-block" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content shadow-lg">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Offer</h5>
                        <button type="button" class="btn-close" aria-label="Close" wire:click="closeOfferModal"></button>
                    </div>

                    <div class="modal-body">
                        <form wire:submit.prevent="save">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" wire:model.live="title">
                                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <livewire:category-selector :categories="$categories" :error="$errors->first('category_id')" />
                            </div>

                            <div class="mb-3">
                                <label for="content" class="form-label">Content</label>
                                <textarea class="form-control @error('content') is-invalid @enderror" id="content" rows="5" wire:model.live="content"></textarea>
                                @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="photos" class="form-label">Photos</label>
                                <input type="file" class="form-control @if($errors->has('photos') || $errors->has('photos.*')) is-invalid @endif" id="photos" wire:model="photos" multiple accept="image/*">
                                <div wire:loading wire:target="photos" class="text-primary small mt-1">
                                    <div class="spinner-border spinner-border-sm" role="status"></div> Uploading previews...
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

                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-secondary" wire:click="closeOfferModal">Cancel</button>
                                <button type="submit" class="btn btn-primary">
                                    <span wire:loading.remove><i class="bi bi-send"></i> Add Offer</span>
                                    <span wire:loading>Saving...</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
