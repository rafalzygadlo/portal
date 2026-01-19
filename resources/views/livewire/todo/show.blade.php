<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('todo.index') }}" class="text-decoration-none">Pomysły</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($todo->title, 30) }}</li>
                </ol>
            </nav>

            <div class="card border-0 overflow-hidden">
                <div class="card-body p-0 p-md-0">
                    <h1 class="fw-bold mb-3">{{ $todo->title }} <span class="badge bg-secondary">{{ $todo->status }}</span></h1>
                    
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                        <div class="text-muted">
                            <i class="bi bi-person-circle me-1"></i> 
                            @if($todo->user)
                                <a href="{{ route('user.profile', $todo->user) }}" class="text-decoration-none text-muted">{{ $todo->user->name }}</a>
                            @else
                                Anonymous
                            @endif
                            <span class="mx-2">&bull;</span>
                            <i class="bi bi-calendar3 me-1"></i> {{ $todo->created_at->format('d.m.Y H:i') }}
                        </div>
                        <div>
                            <livewire:article.vote :model="$todo" :key="'vote-single-'.$todo->id" />
                        </div>
                    </div>

                    <div class="article-content fs-5 lh-lg">
                        {!! nl2br(e($todo->description)) !!}
                    </div>

                    <div class="mt-5">
                        <livewire:comments :model="$todo" />
                    </div>
                </div>
                <div class="card-footer1 bg-white p-0 border-top-0 pt-4 mt-4">
                     <a href="{{ route('todo.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left"></i> Wróć do listy
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
