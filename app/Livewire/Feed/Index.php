<?php

namespace App\Livewire\Feed;

use Livewire\Component;
use App\Models\Feed;

use Livewire\Attributes\Url;
class Index extends Component
{
    #[Url]
    public $perPage = 10; 

    public $hasMore = true; 

    public function loadMore()
    {
        $this->perPage += 10;    
    }

    public function render()
    {
        $promotedItems = Feed::query()
            ->where('is_promoted', true)
            ->inRandomOrder()
            ->limit(9)
            ->get()
            ->values();

        $items = Feed::query()
            ->orderBy('created_at', 'desc')
            ->limit($this->perPage + 1)
            ->get()
            ->values();

        $this->hasMore = $items->count() > $this->perPage;
        $items = $items->take($this->perPage)->values();
  

        return view('livewire.feed.index', [
            'promotedItems' => $promotedItems,
            'regularItems' => $items,
            'hasMore' => $this->hasMore,
            'countAll' => Feed::count(),
        ]);
    }
}
 