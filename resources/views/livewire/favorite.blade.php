<button type="button" wire:click.prevent.stop="toggle" onclick="event.preventDefault(); event.stopPropagation();" class="btn btn-sm {{ $isFavorite ? 'btn-danger' : 'btn-outline-danger' }}" style="position: relative; z-index: 1000; pointer-events: auto;">
    <i class="bi bi-heart{{ $isFavorite ? '-fill' : '' }} me-1"></i>
    {{ $count }}
</button>
