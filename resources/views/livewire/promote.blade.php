<div style="z-index:1">
    @if ($isPromoted)
        <button class="btn btn-sm btn-success disabled" title="Ta treść jest aktualnie promowana">
            <i class="bi bi-check-circle-fill me-1"></i>
            Promowane
        </button>
    @else
        <button  wire:click="openPromoteForm" wire:loading.attr="disabled" class="btn btn-sm btn-outline-warning">
            <span wire:loading.remove wire:target="promote">
                <i class="bi bi-star me-1"></i>
                Promuj
            </span>
            <span wire:loading wire:target="promote" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
        </button>
    @endif
</div>