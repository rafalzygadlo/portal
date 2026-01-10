<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card border-0">
                <div class="card-header bg-white">
                    <h4 class="mb-0 fw-bold">Napisz nowy artykuł</h4>
                </div>
                <div class="card-body p-4">
                    <form wire:submit.prevent="save">
                        <div class="mb-3">
                            <label for="title" class="form-label">Tytuł artykułu</label>
                            <input type="text" class="form-control form-control-lg @error('title') is-invalid @enderror" id="title" wire:model="title" placeholder="Np. Wydarzenie na rynku w Bolesławcu">
                            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="category" class="form-label">Kategoria</label>
                            <select class="form-select form-select-lg @error('category_id') is-invalid @enderror" id="category" wire:model="category_id">
                                <option value="">Wybierz kategorię</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="photo" class="form-label">Zdjęcie główne (opcjonalne)</label>
                            <input type="file" class="form-control form-control-lg @error('photo') is-invalid @enderror" id="photo" wire:model="photo">
                            @error('photo') <div class="invalid-feedback">{{ $message }}</div> @enderror

                            @if ($photo)
                                <img src="{{ $photo->temporaryUrl() }}" class="img-fluid mt-2" style="max-height: 200px;" alt="Podgląd zdjęcia">
                            @endif
                        </div>

                        <div class="mb-4">
                            <label for="content" class="form-label">Treść</label>
                            <textarea class="form-control form-control-lg @error('content') is-invalid @enderror" id="content" rows="10" wire:model="content" placeholder="Opisz szczegóły..."></textarea>
                            @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="/" class="btn btn-outline-secondary me-2">Anuluj</a>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i> Opublikuj artykuł</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
