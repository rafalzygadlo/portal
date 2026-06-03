<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('offers.index') }}" class="text-decoration-none">All Offers</a></li>
        @foreach($path as $item)
            @if($loop->last)
                <li class="breadcrumb-item active" aria-current="page">{{ $item->name }}</li>
            @else
                <li class="breadcrumb-item"><a href="{{ route('offers.index', $item->slug) }}" class="text-decoration-none">{{ $item->name }}</a></li>
            @endif
        @endforeach
    </ol>
</nav>