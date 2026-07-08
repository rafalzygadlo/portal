<div>
    <div class="d-flex align-items-center mb-2">
        @if($this->currentCategoryModel)
            <button type="button" wire:click="goBack" class="btn btn-light border me-2">
                <i class="bi bi-arrow-left"></i>
            </button>
            <span class="fw-bold">{{ $this->currentCategoryModel->name }}</span>
        @endif
    </div>
    <div class="list-group">
        @foreach($categories as $category)
            @if($category->children()->exists())
                {{-- Category with children - navigation item --}}
                <button type="button" wire:click="selectCategory({{ $category->id }})" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    {{ $category->name }}
                    <i class="bi bi-chevron-right"></i>
                </button>
            @else
                {{-- Leaf category - selectable item --}}
                <label wire:click.prevent="selectCategory({{ $category->id }})" class="list-group-item list-group-item-action d-flex align-items-center" style="cursor: pointer;">
                    <input 
                        type="checkbox" 
                        class="form-check-input me-2" 
                        @if(in_array($category->id, $value)) checked @endif
                        tabindex="-1"
                        readonly
                    >
                    {{ $category->name }}
                </label>
            @endif
        @endforeach
    </div>

    @if($this->selectedCategoriesCollection->isNotEmpty())
        {{-- Hidden inputs for form submission --}}
        @foreach($selectedCategoriesCollection as $selected)
            <input type="hidden" name="categories[]" value="{{ $selected->id }}">
        @endforeach

        <div class="mt-3">
            @foreach($selectedCategoriesCollection as $selected)
                <span class="badge text-bg-primary me-1 mb-1">{{ $selected->name }} <button type="button" wire:click="selectCategory({{ $selected->id }})" class="btn-close btn-close-white ms-1" style="font-size: 0.6em;"></button></span>
            @endforeach
        </div>
    @endif

</div>
