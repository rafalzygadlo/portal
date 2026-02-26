<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="d-flex justify-content-end mb-3">
                <button type="button" class="btn btn-primary" wire:click="openModal">
                    <i class="bi bi-plus-circle"></i> Dodaj firmę
                </button>
            </div>

            @if ($showModal)
                <div class="modal fade show d-block" tabindex="-1" role="dialog" aria-modal="true" style="background: rgba(0,0,0,.5);">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Dodaj nową firmę</h5>
                                <button type="button" class="btn-close" aria-label="Close" wire:click="closeModal"></button>
                            </div>

                            <form wire:submit.prevent="save">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nazwa firmy</label>
                                        <input type="text"
                                            class="form-control @error('name') is-invalid @enderror"
                                            id="name"
                                            wire:model.defer="name">
                                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="subdomain" class="form-label">Subdomena</label>
                                        <div class="input-group">
                                            <input type="text"
                                                class="form-control @error('subdomain') is-invalid @enderror"
                                                id="subdomain"
                                                wire:model.defer="subdomain"
                                                placeholder="np. moja-firma">
                                            <span class="input-group-text">.twojadomena.pl</span>
                                            @error('subdomain') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="form-text">Dozwolone: litery, cyfry, myślnik i podkreślenie.</div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" wire:click="closeModal">Anuluj</button>
                                    <button type="submit" class="btn btn-primary">
                                        <span wire:loading.remove>Zapisz</span>
                                        <span wire:loading>Zapisywanie...</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
