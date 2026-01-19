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

    <div class="list-group">
        @forelse ($polls as $poll)
            <a href="{{ route('polls.show', $poll->id) }}" class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">{{ $poll->question }}</h5>
                    <small>{{ $poll->created_at->diffForHumans() }}</small>
                </div>
                <small>Autor: {{ $poll->user->name }}</small>
            </a>
        @empty
            <div class="alert alert-secondary">
                Brak ankiet.
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $polls->links() }}
    </div>
</div>
