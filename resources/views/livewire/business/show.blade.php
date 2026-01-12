<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h1>{{ $business->name }}</h1>
            <livewire:business.vote :business="$business" :key="$business->id" />
        </div>
        <div class="card-body">
            <p><strong>Adres:</strong> {{ $business->address }}</p>

            @if($business->categories->isNotEmpty())
                <p><strong>Kategorie:</strong>
                    @foreach($business->categories as $category)
                        <span class="badge bg-secondary">{{ $category->name }}</span>
                    @endforeach
                </p>
            @endif
            
            @if($business->phone)
                <p><strong>Telefon:</strong> {{ $business->phone }}</p>
            @endif

            @if($business->website)
                <p><strong>Strona WWW:</strong> <a href="{{ $business->website }}" target="_blank">{{ $business->website }}</a></p>
            @endif

            <hr>

            <p>{{ nl2br(e($business->description)) }}</p>
        </div>
        <div class="card-footer text-muted">
            Dodano: {{ $business->created_at->format('d.m.Y') }} przez {{ $business->user->first_name }}
        </div>
    </div>
</div>
