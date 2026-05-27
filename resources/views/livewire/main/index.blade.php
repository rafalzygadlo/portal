<div class="container-fluid">
    @include('livewire/main/partials/header')
    <div class="row g-3 g-md-6">
        @foreach ($items as $item)

             @php
            
                // Nowoczesny, dynamiczny podział siatki
                if ($loop->first) {
                    $colClass = 'col-12 col-lg-8'; // Główny artykuł dnia
                } elseif ($loop->iteration > 1 && $loop->iteration <= 3) {
                    $colClass = 'col-12 col-md-6 col-lg-4'; // Dwa mniejsze artykuły obok głównego
                } else {
                    $colClass = 'col-12 col-sm-6 col-md-4'; // Pozostałe w równej siatce
                }
            @endphp


            <div class="{{ $colClass }}" >

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
