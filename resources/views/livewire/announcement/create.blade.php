<div>
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card border-1 shadow">
                <div class="card-header bg-white">Dodaj nowe ogłoszenie</div>

                <div class="card-body">

                    <form wire:submit.prevent="save">
                        <div class="mb-3">
                            <label for="title" class="form-label">Tytuł</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" wire:model.defer="title">
                            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="announcement_category_id" class="form-label">Kategoria</label>
                            <select class="form-control @error('announcement_category_id') is-invalid @enderror" id="announcement_category_id" wire:model.live="announcement_category_id">
                                <option value="0">Wybierz kategorię...</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('announcement_category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        @if($category_slug === 'praca')
                            <div class="mb-3">
                                <label for="salary" class="form-label">Wynagrodzenie</label>
                                <input type="text" class="form-control @error('salary') is-invalid @enderror" id="salary" wire:model.defer="salary">
                                @error('salary') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        @endif

                        @if($category_slug === 'motoryzacja')
                            <div class="mb-3">
                                <label for="price" class="form-label">Cena</label>
                                <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" wire:model.defer="price">
                                @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="photos" class="form-label">Galeria</label>
                                <input type="file" class="form-control @error('photos.*') is-invalid @enderror" id="photos" wire:model="photos" multiple>
                                @error('photos.*') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            
                            @if ($photos)
                                <div class="mb-3">
                                    Zdjęcia:
                                    <div class="row">
                                    @foreach ($photos as $photo)
                                        <div class="col-3">
                                            <img src="{{ $photo->temporaryUrl() }}" class="img-fluid">
                                        </div>
                                    @endforeach
                                    </div>
                                </div>
                            @endif
                        @endif

                        <div class="mb-3">
                            <label for="content" class="form-label">Treść</label>
                            <textarea class="form-control @error('content') is-invalid @enderror" id="content" rows="5" wire:model.defer="content"></textarea>
                            @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('announcements.index') }}" class="btn btn-outline-secondary me-2">Anuluj</a>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i>
                                <span wire:loading.remove>Dodaj ogłoszenie</span>
                                <span wire:loading>Zapisywanie...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
