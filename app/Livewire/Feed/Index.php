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
            ->when(! config('modules.article'), fn ($query) => $query->where('type', '!=', 'article'))
            ->inRandomOrder()
            ->limit(6)
            ->get()
            ->filter(fn (Feed $item) => $item->item !== null)
            ->values();

        $items = Feed::query()->
        when(! config('modules.article'), fn ($query) => $query->where('type', '!=', 'article'))
        ->orderBy('created_at', 'desc')
        ->limit($this->perPage)
        ->get()
        ->filter(fn (Feed $item) => $item->item !== null)
        ->values();
  

        return view('livewire.feed.index', [
            'promotedItems' => $promotedItems,
            'regularItems' => $items,
            'hasMore' => true,
        ]);
    }
}
 