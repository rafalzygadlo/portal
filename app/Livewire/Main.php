<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Offer;
use App\Models\Article;
use App\Models\Todo;
use App\Models\Promotion;
use App\Models\Business;


use Livewire\Attributes\On;
class Main extends AuthComponent
{
    #[Url]
    public $perPage = 10; 

    public $hasMore = true; 

    public function loadMore()
    {
        $this->perPage += 10;
        //sleep(1); // Opcjonalnie, aby zasymulować opóźnienie ładowania
    }

    public function __checkAuthAndOpenModal($component, $title)
    {
        if (auth()->guest()) {
            // Zapisujemy parametry modala w sesji przed przekierowaniem
            session()->put('intended_modal', [
                'component' => $component,
                'title' => $title
            ]);

            return;// redirect()->guest(route('login'));
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
        //if (session()->has('intended_modal')) {
        //    $modal = session()->pull('intended_modal');
        
            // Wyzwalany zdarzenie otwarcia modala
        //    $this->dispatch('openModal', $modal['component'], $modal['title']);
        //}
    }


    public function render()
    {
        // 1. Pobierz promowane elementy
        $promotedItems = Promotion::with('promotable.categories', 'promotable.images', 'promotable.user')
            ->where('expires_at', '>', now())
            ->latest('promotions.created_at') // Najnowsze promocje na górze
            ->get()
            ->map(function ($promotion) {
                // Ustal typ na podstawie klasy modelu
                $type = strtolower(class_basename($promotion->promotable_type));
                return ['type' => $type, 'data' => $promotion->promotable, 'is_promoted' => true];
            })->unique('data.id'); // Unikaj duplikatów, jeśli coś jest promowane wielokrotnie

        $promotedIds = $promotedItems->pluck('data.id')->all();
        $promotedTypes = $promotedItems->pluck('data')->map(fn($item) => get_class($item))->unique()->all();

        // 2. Pobierz pozostałe elementy, wykluczając te już promowane
        $articles = Article::with(['categories', 'images'])
            ->whereNotIn('id', $promotedTypes[Article::class] ?? [])
            ->latest()
            ->limit($this->perPage)
            ->get()
            ->map(fn($i) => ['type' => 'article', 'data' => $i]);
        
        $todos = Todo::latest()
            ->whereNotIn('id', $promotedTypes[Todo::class] ?? [])
            ->limit($this->perPage)
            ->get()
            ->map(fn($i) => ['type' => 'todo', 'data' => $i]);

        $business = Business::with(['categories'])
            ->whereNotIn('id', $promotedTypes[Business::class] ?? [])
            ->latest()
            ->limit($this->perPage)
            ->get()
            ->map(fn($i) => ['type' => 'business', 'data' => $i]);

        $offers = Offer::with(['categories', 'images'])
            ->whereNotIn('id', $promotedTypes[Offer::class] ?? [])
            ->latest()
            ->limit($this->perPage)
            ->get()
            ->map(fn($i) => ['type' => 'offer', 'data' => $i]);

        // 3. Połącz promowane z resztą
        $otherItems = $articles->concat($todos)->concat($business)->concat($offers)
            ->sortByDesc('data.created_at')
            ->unique(fn($item) => $item['type'] . '-' . $item['data']->id);

        $items = $promotedItems->concat($otherItems)->take($this->perPage);

        // Sprawdzenie, czy jest więcej elementów do załadowania
        $totalCount = Article::count() + Todo::count() + Business::count() + Offer::count();
        $this->hasMore = $totalCount > $items->count();
        
        return view('livewire.main.index', compact('items'));
    }
}
 