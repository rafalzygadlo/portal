<div>
    @if($reported)
        <div class="alert alert-success d-inline-block">
            <i class="bi bi-check-circle-fill me-2"></i> Dziękujemy za zgłoszenie. Administrator sprawdzi ten artykuł.
        </div>
    @else
        @if(!$showForm)
            <button wire:click="toggleForm" class="btn btn-link text-danger text-decoration-none p-0">
                <i class="bi bi-flag"></i> Zgłoś naruszenie
            </button>
        @else
            <div class="card mt-2 border-danger" style="max-width: 500px;">
                <div class="card-body bg-light">
                    <h6 class="card-title text-danger fw-bold mb-3">Zgłoś ten artykuł</h6>
                    <form wire:submit.prevent="save">
                        <div class="mb-3">
                            <textarea wire:model="reason" class="form-control @error('reason') is-invalid @enderror" rows="3" placeholder="Opisz krótko, dlaczego zgłaszasz ten artykuł..."></textarea>
                            @error('reason') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" wire:click="toggleForm" class="btn btn-sm btn-outline-secondary">Anuluj</button>
                            <button type="submit" class="btn btn-sm btn-danger">Wyślij zgłoszenie</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    @endif
</div>