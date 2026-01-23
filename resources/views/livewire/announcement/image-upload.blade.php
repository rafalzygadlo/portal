<div>
    @if (session()->has('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    <div class="mb-3">
        <label for="images" class="form-label">Upload Images</label>
        <input type="file" class="form-control" id="images" wire:model="images" multiple>
        @error('images.*') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    @if (count($images) > 0)
        <div class="mb-3">
            <h5>New Images Preview:</h5>
            <div class="d-flex flex-wrap gap-2">
                @foreach ($images as $image)
                    <img src="{{ $image->temporaryUrl() }}" style="width: 100px; height: 100px; object-fit: cover;">
                @endforeach
            </div>
            <button class="btn btn-primary mt-2" wire:click="saveImages">Save New Images</button>
        </div>
    @endif

    @if (count($existingImages) > 0)
        <div class="mb-3">
            <h5>Existing Images:</h5>
            <div class="d-flex flex-wrap gap-2">
                @foreach ($existingImages as $media)
                    <div class="position-relative">
                        <img src="{{ $media->getUrl() }}" style="width: 100px; height: 100px; object-fit: cover;">
                        <button class="btn btn-danger btn-sm position-absolute top-0 end-0" wire:click="removeImage({{ $media->id }})">&times;</button>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
