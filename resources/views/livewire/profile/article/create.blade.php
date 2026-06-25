<div class="container mt-4">
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0"><i class="bi bi-plus-lg me-2"></i> Write New Article</h5>
                </div>
                <div class="card-body p-4">
                    <ul class="nav nav-tabs mb-4" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link @if($mode === 'edit') active @endif" wire:click.prevent="edit" type="button">
                                <i class="bi bi-pencil me-2"></i> Edit
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link @if($mode === 'preview') active @endif" wire:click.prevent="preview" type="button">
                                <i class="bi bi-eye me-2"></i> Preview
                            </button>
                        </li>
                    </ul>

                    @if($mode === 'edit')
                        <form wire:submit.prevent="save">
                            <div class="mb-3">
                                <label for="title" class="form-label fw-semibold">Title</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" wire:model="title" placeholder="Article title">
                                @error('title') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Categories</label>
                                <div>
                                    @foreach($allCategories as $category)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="category-{{ $category->id }}" value="{{ $category->id }}" wire:model="categories">
                                            <label class="form-check-label" for="category-{{ $category->id }}">{{ $category->name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                                @error('categories') <div class="text-danger mt-1 small">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="content" class="form-label fw-semibold">Content</label>
                                <textarea class="form-control @error('content') is-invalid @enderror" id="content" rows="10" wire:model="content" placeholder="Write your article here..."></textarea>
                                @error('content') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Photos</label>
                                <div wire:loading wire:target="photos" class="text-primary small mb-2">
                                    <div class="spinner-border spinner-border-sm me-2" role="status"></div>Przesyłanie zdjęć...
                                </div>
                                <livewire:upload.gallery
                                    wire:model="photos"
                                    inputId="article-photos-create"
                                    field="photos"
                                    title="Article photos"
                                    :showReorder="true"
                                    :errorFields="['photos', 'photos.*']"
                                    :key="'article-upload-create'"
                                />
                            </div>

                            <div class="d-flex justify-content-between gap-2 mt-4">
                                <a href="{{ route('user.profile') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-2"></i> Back
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <span wire:loading.remove><i class="bi bi-send me-2"></i> Publish</span>
                                    <span wire:loading><span class="spinner-border spinner-border-sm me-2" role="status"></span> Publishing...</span>
                                </button>
                            </div>
                        </form>
                    @elseif($mode === 'preview')
                        <div class="card border-0 bg-light p-4">
                            <h3>{{ $title ?: 'Article Title' }}</h3>
                            <div class="text-muted small mb-3">
                                Categories: 
                                @if($categories)
                                    {{ implode(', ', $allCategories->whereIn('id', $categories)->pluck('name')->toArray()) }}
                                @else
                                    <span class="text-danger">No categories selected</span>
                                @endif
                            </div>
                            <hr>
                            <div class="mt-3">
                                {!! nl2br(e($content)) !!}
                            </div>
                        </div>
                        <div class="d-flex justify-content-between gap-2 mt-4">
                            <button type="button" class="btn btn-secondary" wire:click.prevent="edit">
                                <i class="bi bi-arrow-left me-2"></i> Back to edit
                            </button>
                            <button type="button" class="btn btn-primary" wire:click.prevent="save">
                                <i class="bi bi-send me-2"></i> Publish article
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
