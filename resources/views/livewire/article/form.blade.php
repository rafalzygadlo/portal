<div class="container-fluid py-5" x-data="{ tab: 'edit' }">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card border-0">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0 fw-bold">Napisz nowy artykuł</h4>
                        <ul class="nav nav-pills card-header-pills">
                            <li class="nav-item">
                                <a class="nav-link" :class="{ 'active': tab === 'edit', 'text-dark': tab !== 'edit' }" href="#" @click.prevent="tab = 'edit'"><i class="bi bi-pencil"></i> Edycja</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" :class="{ 'active': tab === 'preview', 'text-dark': tab !== 'preview' }" href="#" @click.prevent="tab = 'preview'"><i class="bi bi-eye"></i> Podgląd</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form wire:submit.prevent="save">
                        <div x-show="tab === 'edit'">
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
                            <div wire:ignore>
                                <input type="file" id="quill-image-input" class="d-none" accept="image/*">
                                <div id="quill-editor" style="min-height: 300px; background-color: #fff;">{!! $content !!}</div>
                                <script>
                                    var quill = new Quill('#quill-editor', {
                                        theme: 'snow',
                                        placeholder: 'Opisz szczegóły...',
                                        modules: {
                                            toolbar: {
                                                container: [
                                                    [{ 'header': [2, 3, false] }],
                                                    ['bold', 'italic', 'underline', 'strike'],
                                                    ['blockquote', 'code-block', 'link'],
                                                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                                                    ['image'],
                                                    ['clean']
                                                ],
                                                handlers: {
                                                    image: function() {
                                                        document.getElementById('quill-image-input').click();
                                                    }
                                                }
                                            }
                                        }
                                    });

                                    // Obsługa wyboru pliku z dysku
                                    document.getElementById('quill-image-input').addEventListener('change', function() {
                                        if (this.files && this.files[0]) uploadFile(this.files[0]);
                                    });

                                    // Obsługa Drag & Drop
                                    quill.root.addEventListener('drop', function(e) {
                                        e.preventDefault();
                                        if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files.length) {
                                            uploadFile(e.dataTransfer.files[0]);
                                        }
                                    });

                                    function uploadFile(file) {
                                        const range = quill.getSelection(true) || { index: quill.getLength() };
                                        quill.insertText(range.index, 'Wgrywanie...', 'bold', true);
                                        
                                        @this.upload('editorImage', file, (uploadedFilename) => {
                                            @this.call('getEditorImageUrl', uploadedFilename).then(url => {
                                                quill.deleteText(range.index, 'Wgrywanie...'.length);
                                                quill.insertEmbed(range.index, 'image', url);
                                            });
                                        }, () => {
                                            quill.deleteText(range.index, 'Wgrywanie...'.length);
                                            alert('Błąd wgrywania zdjęcia');
                                        });
                                    }

                                    quill.on('text-change', function () {
                                        @this.set('content', quill.root.innerHTML);
                                    });
                                </script>
                            </div>
                            @error('content') <div class="text-danger mt-1 small">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="/" class="btn btn-outline-secondary me-2">Anuluj</a>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i> Opublikuj artykuł</button>
                        </div>
                        </div>

                        <div x-show="tab === 'preview'" style="display: none;">
                            <div class="article-preview">
                                <h1 class="fw-bold mb-3">{{ $title ?: 'Brak tytułu' }}</h1>
                                
                                @if ($photo)
                                    <img src="{{ $photo->temporaryUrl() }}" class="img-fluid mb-4 rounded" style="max-height: 400px; width: 100%; object-fit: cover;" alt="Podgląd zdjęcia">
                                @endif

                                <div class="article-content fs-5 lh-lg">
                                    {!! $content !!}
                                </div>

                                <div class="mt-5 pt-3 border-top text-center">
                                    <button type="button" @click="tab = 'edit'" class="btn btn-outline-secondary me-2">Wróć do edycji</button>
                                    <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i> Opublikuj artykuł</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
