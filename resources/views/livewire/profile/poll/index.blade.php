<div class="card border-0">
    <div class="card-body">
        @if($polls->count() > 0)
            <div class="list-group list-group-flush">
                @foreach($polls as $poll)
                    <div class="list-group-item d-flex justify-content-between align-items-start px-0 py-3">
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-bold">{{ $poll->question }}</h6>
                            <small class="text-muted">Options: {{ $poll->options->count() }} | Created: {{ $poll->created_at->format('d.m.Y H:i') }}</small>
                        </div>
                        <div class="d-flex gap-2 ms-2">
                            <a href="{{ route('poll.show', $poll) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                <i class="bi bi-eye"></i> View
                            </a>
                            <a href="{{ route('profile.poll.show', $poll) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-light text-center py-5">
                <i class="bi bi-bar-chart text-muted" style="font-size: 2rem;"></i>
                <p class="mt-3 text-muted">You haven't created any polls yet.</p>
                <a href="{{ route('profile.poll.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg"></i> Create your first poll
                </a>
            </div>
        @endif
    </div>
</div>
