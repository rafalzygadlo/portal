<div>
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card border-1 shadow">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                       Napisz nowy artykuł
                        <ul class="nav nav-pills card-header-pills">
                            <li class="nav-item">
                                <a class="nav-link {{ $mode === 'edit' ? 'active' : 'text-dark' }}" href="#" wire:click.prevent="edit"><i class="bi bi-pencil"></i> Edycja</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $mode === 'preview' ? 'active' : 'text-dark' }}" href="#" wire:click.prevent="preview"><i class="bi bi-eye"></i> Podgląd</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form wire:submit.prevent="save">
                        <div style="display: none;">
                            <label for="honey_pot">Don't fill this out if you're human:</label>
                            <input type="text" id="honey_pot" name="honey_pot" wire:model="honey_pot" autocomplete="off">
                        </div>
                        @if ($mode === 'edit')
                        <div>
                        <div class="mb-3">
                            <label for="title" class="form-label">Tytuł artykułu</label>
                            <input type="text" class="form-control form-control-lg @error('title') is-invalid @enderror" id="title" wire:model="title" placeholder="Np. Wydarzenie na rynku w Bolesławcu">
                            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        {{--
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
                        --}}
                        <div class="mb-4">
                            <label for="content" class="form-label">Treść</label>
                            <textarea class="form-control form-control-lg @error('content') is-invalid @enderror" id="content" wire:model="content" rows="10" placeholder="Opisz szczegóły..."></textarea>
                            @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="/" class="btn btn-outline-secondary me-2">Anuluj</a>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i> Opublikuj artykuł</button>
                        </div>
                        </div>
                        @endif

                        @if ($mode === 'preview')
                        <div>
                            @include('livewire.article.preview')
                        </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
