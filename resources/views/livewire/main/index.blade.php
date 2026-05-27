<div class="container-fluid">
    @include('livewire/main/partials/header')
    <div class="row g-3 g-md-4">
        @foreach ($items as $item)

            <div class="col col-md-6 col-lg-4 col-xl-3">

            @php
                $viewPath = 'livewire.main.partials.' . $item['type'];
            @endphp
        
            @if (view()->exists($viewPath))
                @include($viewPath, ['item' => $item['data']])
            @else
            {{-- Mechanizm bezpieczeństwa --}}
            <div class="alert alert-warning p-2">
                <small>Błąd: Brak widoku dla typu: {{ $item['type'] }}</small>
            </div>
            @endif
        
        </div>
        @endforeach

        
    </div> 
</div>
