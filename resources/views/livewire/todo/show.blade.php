<div class="col">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif

            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb small">
                    <li class="breadcrumb-item"><a href="{{ route('todos.index') }}" class="text-decoration-none">Ideas</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($todo->title, 30) }}</li>
                </ol>
            </nav>

            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
                        <h1 class="fw-bold mb-0 display-6">{{ $todo->title }}</h1>
                        <span class="badge bg-{{ $todo->getStatusColor() }} rounded-pill px-3 py-2 shadow-sm">
                            {{ $todo->status }}
                        </span>
                    </div>

                    <div class="d-flex flex-wrap justify-content-between align-items-center border-bottom pb-3 mb-4 gap-3">
                        <div class="text-muted d-flex align-items-center flex-wrap gap-2">
                            <span class="d-flex align-items-center">
                                <i class="bi bi-person-circle me-1"></i> 
                                @if($todo->user)
                                    <a href="{{ route('user.profile', $todo->user) }}" class="text-decoration-none text-dark fw-medium">{{ $todo->user->name }}</a>
                                @else
                                    Anonymous
                                @endif
                            </span>
                            <span class="text-muted d-none d-sm-inline">•</span>
                            <span class="d-flex align-items-center">
                                <i class="bi bi-calendar3 me-1"></i> {{ $todo->created_at->format('d.m.Y H:i') }}
                            </span>
                        </div>
                        {{--
                        <div class="bg-light rounded-pill px-2 py-1">
                            <livewire:article.vote :model="$todo" :key="'vote-single-'.$todo->id" />
                        </div>
                        --}}
                    </div>

                    <div class="article-content fs-5 lh-lg text-secondary">
                        {!! nl2br(e($todo->description)) !!}
                    </div>

                    <div class="mt-5">
                        <livewire:comments :model="$todo" />
                    </div>
                </div>
                <div class="card-footer bg-light border-0 p-4">
                     <a href="{{ route('todos.index') }}" class="btn btn-light border shadow-sm px-4">
                        <i class="bi bi-arrow-left me-2"></i> Powrót do listy
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
