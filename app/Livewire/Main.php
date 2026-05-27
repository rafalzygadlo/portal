<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Offer\Offer;
use App\Models\Article\Article;
use App\Models\Todo;
use App\Models\Business;


class Main extends Component
{
    public function render()
    {
        
        // W kontrolerze
        $articles = Article::latest()->with('categories')->with('images')->limit(10)->get()->map(fn($i) => ['type' => 'article', 'data' => $i]);
        $todos = Todo::latest()->limit(10)->get()->map(fn($i) => ['type' => 'todo', 'data' => $i]);
        $business = Business::latest()->with('categories')->limit(10)->get()->map(fn($i) => ['type' => 'business', 'data' => $i]);
        $offers = Offer::latest()->with('categories')->with('images')->limit(10)->get()->map(fn($i) => ['type' => 'offer', 'data' => $i]);

        // Łączymy i sortujemy po dacie (created_at jest w data)
        $items = $articles->concat($todos)->concat($business)->concat($offers)
                 ->sortByDesc('data.created_at'); // Ograniczamy do 10 elementów
        
        return view('livewire.main.index', compact('items'));
    }
}
 