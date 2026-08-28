<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Feed;

use Livewire\Attributes\Url;
class Main extends Component
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
            ->limit(6)
            ->get();

        $promotedIdsByType = $promotedItems->groupBy('type')
            ->map(fn ($items) => $items->pluck('item_id')->all())
            ->all();

        $regularQuery = Feed::query()->where(function ($query) use ($promotedIdsByType) {
            foreach (['article', 'todo', 'business', 'offer'] as $type) {
                $query->orWhere(function ($query) use ($type, $promotedIdsByType) {
                    $query->where('type', $type)
                        ->when(isset($promotedIdsByType[$type]), fn ($query) => $query->whereNotIn('item_id', $promotedIdsByType[$type]));
                });
            }
        });

        $regularCount = (clone $regularQuery)->count();
        $regularItems = $regularQuery
            ->latest('created_at')
            ->limit($this->perPage)
            ->get()
            ->map(fn (Feed $item) => ['type' => $item->type, 'data' => $item->item])
            ->filter(fn ($item) => $item['data'] !== null)
            ->values();

        
        return view('livewire.main.index', [
            'promotedItems' => $promotedItems
                ->map(fn (Feed $item) => ['type' => $item->type, 'data' => $item->item])
                ->filter(fn ($item) => $item['data'] !== null)
                ->values(),
            'regularItems' => $regularItems,
            'hasMore' => $regularCount > $regularItems->count(),
        ]);
    }
}
 