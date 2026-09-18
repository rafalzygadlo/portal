<div class="container mt-4">
    <div class="row">
        <div class="col-lg-6 offset-lg-3">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-pencil me-2"></i> Edit Poll</h5>
                    <button type="button" class="btn btn-sm btn-danger" wire:click="delete" onclick="confirm('Are you sure?') || event.stopImmediatePropagation()">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                </div>
                <div class="card-body p-4">
                    <form wire:submit.prevent="save">
                        <div class="mb-3">
                            <label for="question" class="form-label fw-semibold">Question</label>
                            <input type="text" class="form-control @error('question') is-invalid @enderror" id="question" wire:model="question">
                            @error('question') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Options</label>
                            @foreach ($options as $index => $option)
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control @error('options.' . $index) is-invalid @enderror" wire:model="options.{{ $index }}">
                                    <button type="button" class="btn btn-outline-danger" wire:click="removeOption({{ $index }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    @error('options.' . $index) <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                            @endforeach
                        </div>

                        <button type="button" class="btn btn-outline-secondary btn-sm mb-3" wire:click="addOption">
                            <i class="bi bi-plus-lg"></i> Add option
                        </button>
                        @error('options') <div class="text-danger small mb-3">{{ $message }}</div> @enderror

                        <div class="d-flex justify-content-between gap-2 mt-4">
                            <a href="{{ route('user.profile') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <span wire:loading.remove><i class="bi bi-save me-2"></i> Save</span>
                                <span wire:loading><span class="spinner-border spinner-border-sm me-2" role="status"></span> Saving...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
