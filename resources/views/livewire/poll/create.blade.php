<div>
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-1">
                <div class="card-header">Dodaj nową ankietę</div>

                <div class="card-body">

                    <form wire:submit.prevent="createPoll">
                        <div class="mb-3">
                            <label for="question" class="form-label">Pytanie</label>
                            <input type="text" class="form-control @error('question') is-invalid @enderror" id="question" wire:model.defer="question">
                            @error('question') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Opcje</label>
                            @foreach ($options as $index => $option)
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control @error('options.'.$index) is-invalid @enderror" wire:model.defer="options.{{ $index }}">
                                    <button type="button" class="btn btn-danger" wire:click="removeOption({{ $index }})">Usuń</button>
                                    @error('options.'.$index) <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-start mb-3">
                            <button type="button" class="btn btn-outline-secondary" wire:click="addOption">Dodaj opcję</button>
                        </div>
                        
                        @error('options') <div class="text-danger mb-3">{{ $message }}</div> @enderror

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('polls.index') }}" class="btn btn-outline-secondary me-2">Anuluj</a>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i>
                                <span wire:loading.remove>Dodaj ankietę</span>
                                <span wire:loading>Zapisywanie...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
