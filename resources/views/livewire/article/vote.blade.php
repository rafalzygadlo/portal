<div class="d-inline-flex align-items-center gap-1">
    <div class="btn-group" role="group">
        <button wire:click="vote('up')" class="btn btn-sm {{ $userVote === 'up' ? 'btn-success' : 'btn-outline-success' }}" {{ $isAuthor ? 'disabled' : '' }} title="Dobre">
            <i class="bi bi-hand-thumbs-up{{ $userVote === 'up' ? '-fill' : '' }}"></i>
        </button>
        <button class="btn btn-sm btn-light disabled text-dark fw-bold" style="opacity: 1; min-width: 40px;">
            {{ $votesCount > 0 ? '+' : '' }}{{ $votesCount }}
        </button>
        <button wire:click="vote('down')" class="btn btn-sm {{ $userVote === 'down' ? 'btn-danger' : 'btn-outline-danger' }}" {{ $isAuthor ? 'disabled' : '' }} title="Słabe">
            <i class="bi bi-hand-thumbs-down{{ $userVote === 'down' ? '-fill' : '' }}"></i>
        </button>
    </div>
</div>