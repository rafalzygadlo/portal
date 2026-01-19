<div>
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card border-1 shadow">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between">
                        <div>{{ $announcement->title }}</div>
                        <div><a href="{{ route('announcements.index') }}" class="btn btn-outline-secondary btn-sm">Powrót</a></div>
                    </div>
                </div>

                <div class="card-body">
                    <p><strong>Kategoria:</strong> {{ $announcement->category->name }}</p>

                    @if($announcement->salary)
                        <p><strong>Wynagrodzenie:</strong> {{ $announcement->salary }}</p>
                    @endif

                    @if($announcement->price)
                        <p><strong>Cena:</strong> {{ $announcement->price }} zł</p>
                    @endif
                    
                    <p>{{ $announcement->content }}</p>

                    @if($announcement->photos->count() > 0)
                        <div class="mt-4">
                            <h4>Galeria</h4>
                            <div class="row">
                                @foreach($announcement->photos as $photo)
                                    <div class="col-md-4 mb-3">
                                        <img src="{{ Storage::url($photo->path) }}" class="img-fluid">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
                <div class="card-footer bg-white text-muted">
                    Dodano przez: {{ $announcement->user->name }}
                </div>
            </div>
        </div>
    </div>
</div>