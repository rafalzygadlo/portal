<div class="d-flex flex-column align-items-center">
    <i 
        class="bi bi-arrow-up-circle-fill fs-4" 
        style="cursor: pointer; color: {{ $userVote === 1 ? 'green' : 'currentColor' }};"
        wire:click="upvote">
    </i>
    
    <span class="fs-5 fw-bold my-1">{{ $score }}</span>
    
    <i 
        class="bi bi-arrow-down-circle-fill fs-4" 
        style="cursor: pointer; color: {{ $userVote === -1 ? 'red' : 'currentColor' }};"
        wire:click="downvote">
    </i>
</div>
