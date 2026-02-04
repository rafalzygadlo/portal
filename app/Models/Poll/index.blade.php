<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Ankiety</h2>
        <a href="{{ route('polls.create') }}" class="btn btn-primary">Dodaj ankietę</a>
    </div>

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <div class="row">
        @forelse ($polls as $poll)
            @php
                $hasVoted = optional($poll->votes)->contains('user_id', auth()->id()) 
                    || ($poll->options && $poll->options->pluck('votes')->flatten()->contains('user_id', auth()->id()));
            @endphp
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 {{ $hasVoted ? 'border-dark' : '' }}">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="card-title mb-0">{{ $poll->question }}</h5>
                            <small class="text-muted text-nowrap ms-2">{{ $poll->created_at->diffForHumans() }}</small>
                        </div>
                        <div class="mt-auto small text-muted d-flex justify-content-between align-items-center">
                            <span>Autor: {{ $poll->user->name }}</span>
                            @if($hasVoted)
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-check-circle-fill text-dark" viewBox="0 0 16 16">
                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                </svg>
                            @endif
                        </div>
                        <a href="{{ route('polls.show', $poll->id) }}" class="stretched-link"></a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-secondary">
                    Brak ankiet.
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $polls->links() }}
    </div>
</div>
