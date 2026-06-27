<div>
    <div>
        @if($parentId)
            <button type="button" wire:click="goBack" class="btn bg-white border">
                <i class="bi bi-chevron-left"></i> {{ __('global.back') }}
            </button>
        @endif

        @foreach($categories as $category)
            <button 
                type="button"
                wire:click="selectCategory({{ $category->id }})"
                class="btn text-start border {{ $value == $category->id ? 'btn-primary' : 'bg-white' }}">
                {{ $category->name }}
                @if($category->children()->exists())
                     <i class="bi bi-chevron-right float-end"></i>
                @endif
            </button>
        @endforeach
    </div>

    @if($value)
        <input type="hidden" name="category_id" value="{{ $value }}">
        <div class="mt-2 py-1 px-2 small">
            {{ __('global.selected_category') }}
        <span class="badge bg-primary">{{ $name }}</span>
        </div>
    @endif

</div>

