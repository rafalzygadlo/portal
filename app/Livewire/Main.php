<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Offer;
use App\Models\Article;
use App\Models\Todo;
use App\Models\Business;
use App\Services\MainFeedService;


use Livewire\Attributes\On;
class Main extends Component
{
    #[Url]
    public $perPage = 10; 

    public $hasMore = true; 

    public function loadMore()
    {
        $this->perPage += 10;
        //sleep(1); // Opcjonalnie, aby zasymulować opóźnienie ładowania
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
        $feed = (new MainFeedService())->buildFeed($this->perPage, 6);

        return view('livewire.main.index', [
            'promotedItems' => $feed['promotedItems'],
            'regularItems' => $feed['regularItems'],
            'hasMore' => $feed['hasMore'],
        ]);
    }
}
 