<div class="d-inline-block">
    <button wire:click="vote" class="btn {{ $hasVoted ? 'btn-primary' : 'btn-outline-primary' }}">
        <i class="bi bi-hand-thumbs-up{{ $hasVoted ? '-fill' : '' }}"></i> 
        Głosuj
        <span class="badge bg-white text-dark ms-2">{{ $votesCount }}</span>
    </button>
</div>