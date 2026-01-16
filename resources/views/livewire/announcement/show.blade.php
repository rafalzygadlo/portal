<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Strona główna</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('announcements.index') }}" class="text-decoration-none">Ogłoszenia</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($announcement->title, 30) }}</li>
                </ol>
            </nav>

            <div class="card border-0 overflow-hidden">
                <div class="card-body p-0 p-md-0">
                    <h1 class="fw-bold mb-3">{{ $announcement->title }}</h1>
                    
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                        <div class="text-muted">
                            <i class="bi bi-person-circle me-1"></i> 
                            @if($announcement->user)
                                <a href="{{ route('user.profile', $announcement->user) }}" class="text-decoration-none text-muted">{{ $announcement->user->name }}</a>
                            @else
                                Anonymous
                            @endif
                            <span class="mx-2">&bull;</span>
                            <i class="bi bi-calendar3 me-1"></i> {{ $announcement->created_at->format('d.m.Y H:i') }}
                            @if($announcement->category)
                                <span class="badge bg-secondary ms-2">{{ $announcement->category->name }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="article-content fs-5 lh-lg">
                        {!! nl2br(e($announcement->content)) !!}
                    </div>

                </div>
                <div class="card-footer1 bg-white p-0 border-top-0 mt-4">
                     <a href="{{ route('announcements.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left"></i> Wróć do listy
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
