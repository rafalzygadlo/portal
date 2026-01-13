<div>
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Pagination Navigation">
            <span>
                @if ($paginator->onFirstPage())
                    <span><</span>
                @else
                    <button wire:click="previousPage" wire:loading.attr="disabled" rel="prev"><</button>
                @endif
            </span>
 
            <span>
                @if ($paginator->onLastPage())
                    <span>></span>
                @else
                    <button wire:click="nextPage" wire:loading.attr="disabled" rel="next">></button>
                @endif
            </span>
        </nav>
    @endif
</div>