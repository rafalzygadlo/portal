<div>
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card border-0 shadow">
                <div class="card-header bg-white">Dodaj nowy pomysł</div>

                <div class="card-body">

                    <div class="col-md-0">
                        <form wire:submit.prevent="save">
                            <div class="mb-3">
                                <label for="title" class="form-label">Tytuł</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" wire:model.defer="title">
                                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Opis</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" rows="5" wire:model.defer="description"></textarea>
                                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="d-flex justify-content-end">
                                <a href="{{ route('todos.index') }}" class="btn btn-outline-secondary me-2">Anuluj</a>
                                <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i>
                                    <span wire:loading.remove>Dodaj pomysł</span>
                                    <span wire:loading>Zapisywanie...</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>