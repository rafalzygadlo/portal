<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Offer\Offer;
use App\Models\Article\Article;
use App\Models\Todo;
use App\Models\Business;


class Main extends Component
{
    public $perPage = 10; // Lepiej zacząć od 10 dla lepszego wypełnienia ekranu

    public function loadMore()
    {
        $this->perPage += 10;
        sleep(1); // Opcjonalnie, aby zasymulować opóźnienie ładowania
    }

    public function checkAuthAndOpenModal($component, $title)
    {
        if (auth()->guest()) {
            // Zapisujemy parametry modala w sesji przed przekierowaniem
            session()->put('intended_modal', [
                'component' => $component,
                'title' => $title
            ]);

            return redirect()->guest(route('login'));
        }

        // Sprawdzamy czy użytkownik ma zweryfikowany adres email
        if (! auth()->user()->hasVerifiedEmail()) {
            session()->put('intended_modal', [
                'component' => $component,
                'title' => $title
            ]);

            return redirect()->route('verification.notice');
        }

        // Jeśli zalogowany, otwórz modal od razu
        $this->dispatch('openModal', $component,  $title);
    }

    public function mount()
    {
        // Sprawdzamy czy w sesji czeka modal do otwarcia (wywoływane po powrocie z logowania)
        if (session()->has('intended_modal')) {
            $modal = session()->pull('intended_modal');
        
            // Wyzwalany zdarzenie otwarcia modala
            $this->dispatch('openModal', $modal['component'], $modal['title']);
        }
    }


    public function render()
    {
        // Pobieramy dane z użyciem Eager Loading (with)
        // Łączymy relacje w tablice, aby kod był czytelniejszy
        $articles = Article::with(['categories', 'images'])
            ->latest()
            ->limit($this->perPage)
            ->get()
            ->map(fn($i) => ['type' => 'article', 'data' => $i]);
        
        $todos = Todo::latest()
            ->limit($this->perPage)
            ->get()
            ->map(fn($i) => ['type' => 'todo', 'data' => $i]);

        $business = Business::with(['categories'])
            ->latest()
            ->limit($this->perPage)
            ->get()
            ->map(fn($i) => ['type' => 'business', 'data' => $i]);

        $offers = Offer::with(['categories', 'images'])
            ->latest()
            ->limit($this->perPage)
            ->get()
            ->map(fn($i) => ['type' => 'offer', 'data' => $i]);

        // Łączymy i sortujemy po dacie (created_at jest w data)
        $items = $articles->concat($todos)->concat($business)->concat($offers)
            ->sortByDesc('data.created_at')
            ->take($this->perPage); // KLUCZOWA POPRAWKA: bierzemy tylko tyle, ile wynosi aktualny limit
        
        return view('livewire.main.index', compact('items'));
    }
}
 