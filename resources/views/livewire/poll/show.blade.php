<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Strona główna</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('polls.index') }}" class="text-decoration-none">Ankiety</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($poll->question, 30) }}</li>
                </ol>
            </nav>
            
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <div class="card border-0 overflow-hidden">
                <div class="card-body p-0 p-md-0">
                    <h1 class="fw-bold mb-3">{{ $poll->question }}</h1>

                    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                        <div class="text-muted">
                            <i class="bi bi-person-circle me-1"></i>
                            @if($poll->user)
                                <a href="{{ route('user.profile', $poll->user) }}" class="text-decoration-none text-muted">{{ $poll->user->first_name }}</a>
                            @else
                                Anonymous
                            @endif
                            <span class="mx-2">&bull;</span>
                            <i class="bi bi-calendar3 me-1"></i> {{ $poll->created_at->format('d.m.Y H:i') }}
                        </div>
                    </div>

                    <div class="poll-options fs-5">
                        @foreach ($poll->options as $option)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="pollOption" id="option-{{ $option->id }}" value="{{ $option->id }}" wire:model.live="selectedOptionId">
                                <label class="form-check-label" for="option-{{ $option->id }}">
                                    {{ $option->name }}
                                </label>
                                <span class="text-muted">({{ $option->votes->count() }} głosów)</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer1 bg-white p-0 border-top-0 mt-4">
                    <button class="btn btn-primary" wire:click="vote" @if(!$selectedOptionId) disabled @endif>Głosuj</button>
                    <a href="{{ route('polls.index') }}" class="btn btn-outline-primary ms-2">
                        <i class="bi bi-arrow-left"></i> Wróć do listy
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
