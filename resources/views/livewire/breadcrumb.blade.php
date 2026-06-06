<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route($this->route) }}" class="text-decoration-none"><i class="bi bi-house"></i> Home</a></li>
        @foreach($path as $item)
            @if($loop->last)
                <li class="breadcrumb-item active" aria-current="page">{{ $item->name }}</li>
            @else
                <li class="breadcrumb-item"><a href="{{ route($this->route, $item->slug) }}" class="text-decoration-none">{{ $item->name }}</a></li>
            @endif
        @endforeach
    </ol>
</nav>