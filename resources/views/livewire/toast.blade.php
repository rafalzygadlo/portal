<div>
@if($show)
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
        <div class="alert alert-{{ 
            $type === 'success' ? 'success' : 
            ($type === 'error' ? 'danger' : 
            ($type === 'warning' ? 'warning' : 'info')) 
        }} alert-dismissible fade show shadow" role="alert">
            @if($type === 'success') ✅
            @elseif($type === 'error') ❌
            @elseif($type === 'warning') ⚠️
            @else ℹ️
            @endif
            {{ $message }}
            <button type="button" class="btn-close" wire:click="$set('show', false)" aria-label="Close"></button>
        </div>
    </div>
@endif
</div>