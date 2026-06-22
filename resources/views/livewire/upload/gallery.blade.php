<div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0">{{ $title }}</h6>
        <div class="badge bg-primary text-white">{{ $fileCount }} / {{ $maxPhotos }}</div>
    </div>

    <div class="row g-3">
        @foreach($existingPhotos as $index => $photo)
            <div class="col-6 col-sm-4 col-md-3" wire:key="existing-{{ $index }}">
                <div class="position-relative border rounded overflow-hidden" style="aspect-ratio: 1;">
                    <img src="{{ Storage::url($photo['path']) }}" class="img-fluid w-100 h-100" style="object-fit: cover;">
                    
                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1" wire:click="removeExistingPhoto({{ $index }})">
                        <i class="bi bi-x-lg"></i>
                    </button>

                    @if($showReorder)
                        <div class="position-absolute bottom-0 start-0 w-100 d-flex bg-dark bg-opacity-50">
                            <button type="button" class="btn btn-sm btn-light flex-fill" wire:click="moveExistingPhotoUp({{ $index }})" @if($index == 0) disabled @endif><i class="bi bi-chevron-left"></i></button>
                            <button type="button" class="btn btn-sm btn-light flex-fill" wire:click="moveExistingPhotoDown({{ $index }})" @if($index == count($existingPhotos)-1) disabled @endif><i class="bi bi-chevron-right"></i></button>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach

        @foreach($files as $index => $file)
            <div class="col-6 col-sm-4 col-md-3" wire:key="new-{{ $index }}">
                <div class="position-relative border rounded overflow-hidden" style="aspect-ratio: 1;">
                    <img src="{{ $file->temporaryUrl() }}" class="img-fluid w-100 h-100" style="object-fit: cover;">
                    
                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1" wire:click="removePhoto({{ $index }})">
                        <i class="bi bi-x-lg"></i>
                    </button>

                    @if($showReorder)
                        <div class="position-absolute bottom-0 start-0 w-100 d-flex bg-dark bg-opacity-50">
                            <button type="button" class="btn btn-sm btn-light flex-fill" wire:click="movePhotoUp({{ $index }})" @if($index == 0) disabled @endif><i class="bi bi-chevron-left"></i></button>
                            <button type="button" class="btn btn-sm btn-light flex-fill" wire:click="movePhotoDown({{ $index }})" @if($index == count($files)-1) disabled @endif><i class="bi bi-chevron-right"></i></button>
                        </div>
                    @endif
                </div>
            </div>
            
        @endforeach


        @if($fileCount < $maxPhotos)
            <div class="col-6 col-sm-4 col-md-3">
                <label for="{{ $inputId }}" class="d-flex align-items-center justify-content-center border border-2 border-dashed rounded h-100 cursor-pointer" style="aspect-ratio: 1; cursor: pointer;">
                    <div class="text-center">
                        <i class="bi bi-plus-lg fs-2"></i>
                        <div class="small">Dodaj</div>
                    </div>
                </label>
                
                <input type="file" 
                       id="{{ $inputId }}" 
                       wire:model="files" 
                       multiple 
                       class="d-none" 
                       accept="image/*">
            </div>
        @endif
    </div>
</div>