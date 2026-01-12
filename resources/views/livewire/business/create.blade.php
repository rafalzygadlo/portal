<div>
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card border-1 shadow">
                <div class="card-header bg-white">Dodaj nową firmę</div>

                <div class="card-body">
                    <form wire:submit.prevent="save">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nazwa firmy</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" wire:model.defer="name">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Adres</label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" wire:model.defer="address">
                            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Opis</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" rows="5" wire:model.defer="description"></textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Telefon</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" wire:model.defer="phone">
                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="website" class="form-label">Strona internetowa</label>
                            <input type="url" class="form-control @error('website') is-invalid @enderror" id="website" wire:model.defer="website" placeholder="https://example.com">
                            @error('website') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                         <div class="d-flex justify-content-end">
                            <a href="/" class="btn btn-outline-secondary me-2">Anuluj</a>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i> 
                                <span wire:loading.remove>Dodaj firmę</span>
                                <span wire:loading>Zapisywanie...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
