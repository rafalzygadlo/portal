<div>
    <form wire:submit.prevent="save">
        <div class="mb-3">
            <label for="title" class="form-label">{{ __('global.title') }}</label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                wire:model.defer="title">
            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Opis</label>
            <textarea class="form-control @error('description') is-invalid @enderror" id="description" rows="5"
                wire:model.defer="description"></textarea>
            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="d-flex justify-content-end gap-2">
            <button type="button" class="btn btn-outline-secondary" wire:click="$dispatch('closeModal')">Cancel</button>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i>
                <span wire:loading.remove>{{ __('global.save') }}</span>
                <span wire:loading>Saving...</span>
            </button>
        </div>

    </form>
</div>
