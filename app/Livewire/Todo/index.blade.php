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
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="card-title mb-0">{{ $poll->question }}</h5>
                            <small class="text-muted text-nowrap ms-2">{{ $poll->created_at->diffForHumans() }}</small>
                        </div>
                        <div class="mt-auto small text-muted">
                            <span>Autor: {{ $poll->user->name }}</span>
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
