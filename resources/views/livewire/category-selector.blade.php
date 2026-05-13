<div>
 <label  class="form-label">Kategoria</label>
<div class="p-3 border rounded shadow-sm bg-light">
    <div class="d-grid gap-2">
        @if($parentId)
            <button type="button" wire:click="goBack" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center mb-2 w-auto" style="text-decoration: none;">
                <i class="bi bi-arrow-left"></i> Wróć
            </button>
        @endif

        @foreach($categories as $category)
            <button 
                wire:click="selectCategory({{ $category->id }})"
                class="btn text-start border {{ $selectedCategoryId == $category->id ? 'btn-primary' : 'btn-white bg-white' }}"
            >
                {{ $category->name }}
                @if($category->children()->exists())
                     <i class="bi bi-chevron-right float-end"></i>
                @endif
            </button>
        @endforeach
    </div>

    @if($selectedCategoryId)
        <input type="hidden" name="category_id" value="{{ $selectedCategoryId }}">
        <div class="mt-2 alert alert-success py-1 px-2 small">
            Wybrana kategoria: <strong>{{ $name }}</strong>
        </div>
    @endif

    @if($error)
        <div class="alert alert-danger py-1 px-2 small mt-2 d-block">
            {{ $error }}
        </div>
    @endif
</div>
</div>
