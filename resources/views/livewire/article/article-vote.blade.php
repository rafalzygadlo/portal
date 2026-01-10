<div class="d-inline-block">
    @if($isAuthor)
        <button class="btn btn-outline-secondary" disabled title="Nie możesz głosować na własny artykuł">
            <i class="bi bi-person-check"></i> 
            Twój artykuł
            <span class="badge bg-white text-dark ms-2">{{ $votesCount }}</span>
        </button>
    @else
        <button wire:click="vote" class="btn {{ $hasVoted ? 'btn-primary' : 'btn-outline-primary' }}">
            <i class="bi bi-hand-thumbs-up{{ $hasVoted ? '-fill' : '' }}"></i> 
            Głosuj
            <span class="badge bg-white text-dark ms-2">{{ $votesCount }}</span>
        </button>
    @endif
</div>