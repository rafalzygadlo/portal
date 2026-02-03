<div>
    <h3 class="fw-semibold mb-3 h5">Twoje dane</h3>
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Imię i Nazwisko</label>
            <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror">
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" wire:model="email" class="form-control @error('email') is-invalid @enderror">
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-12">
            <label class="form-label">Telefon (opcjonalnie)</label>
            <input type="tel" wire:model="phone" class="form-control">
        </div>
        <div class="col-12">
            <label class="form-label">Notatki (opcjonalnie)</label>
            <textarea wire:model="notesValue" rows="3" class="form-control"></textarea>
        </div>
    </div>
</div>