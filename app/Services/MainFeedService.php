<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Business;
use App\Models\Offer;
use App\Models\Promotion;
use App\Models\Todo;
use Illuminate\Support\Collection;

class MainFeedService
{
    public function buildFeed(int $perPage = 10, int $promotedLimit = 6): array
    {
        $promotedItems = $this->getPromotedItems($promotedLimit);
        $promotedIdsByType = $this->getPromotedIdsByType($promotedItems);

        $regularItems = $this->getRegularItems($promotedIdsByType, $perPage);
        $hasMore = $this->countRegularItems($promotedIdsByType) > $regularItems->count();

        return compact('promotedItems', 'regularItems', 'hasMore');
    }

    public function getPromotedItems(int $limit = 6): Collection
    {
        return Promotion::with('promotable.categories', 'promotable.images', 'promotable.user')
            ->where('expires_at', '>', now())
            ->inRandomOrder()
            ->get()
            ->map(fn ($promotion) => $this->mapPromotion($promotion))
            ->filter()
            ->unique(fn ($item) => $item['type'] . '-' . $item['data']->id)
            ->take($limit);
    }

    public function getPromotedIdsByType(Collection $promotedItems): array
    {
        return $promotedItems->groupBy('type')
            ->map(fn ($group) => $group->pluck('data.id')->all())
            ->all();
    }

    public function getRegularItems(array $promotedIdsByType, int $limit = 10): Collection
    {
        $articles = Article::with(['categories', 'images'])
            ->when(isset($promotedIdsByType['article']), fn ($query) => $query->whereNotIn('id', $promotedIdsByType['article']))
            ->latest()
            ->limit($limit)
            ->get()
            ->map(fn ($item) => ['type' => 'article', 'data' => $item]);

        $todos = Todo::latest()
            ->when(isset($promotedIdsByType['todo']), fn ($query) => $query->whereNotIn('id', $promotedIdsByType['todo']))
            ->limit($limit)
            ->get()
            ->map(fn ($item) => ['type' => 'todo', 'data' => $item]);

        $business = Business::with(['categories'])
            ->when(isset($promotedIdsByType['business']), fn ($query) => $query->whereNotIn('id', $promotedIdsByType['business']))
            ->latest()
            ->limit($limit)
            ->get()
            ->map(fn ($item) => ['type' => 'business', 'data' => $item]);

        $offers = Offer::with(['categories', 'images'])
            ->when(isset($promotedIdsByType['offer']), fn ($query) => $query->whereNotIn('id', $promotedIdsByType['offer']))
            ->latest()
            ->limit($limit)
            ->get()
            ->map(fn ($item) => ['type' => 'offer', 'data' => $item]);

        return $articles->concat($todos)->concat($business)->concat($offers)
            ->sortByDesc('data.created_at')
            ->unique(fn ($item) => $item['type'] . '-' . $item['data']->id)
            ->take($limit);
    }

    public function countRegularItems(array $promotedIdsByType): int
    {
        return Article::when(isset($promotedIdsByType['article']), fn ($query) => $query->whereNotIn('id', $promotedIdsByType['article']))->count()
            + Todo::when(isset($promotedIdsByType['todo']), fn ($query) => $query->whereNotIn('id', $promotedIdsByType['todo']))->count()
            + Business::when(isset($promotedIdsByType['business']), fn ($query) => $query->whereNotIn('id', $promotedIdsByType['business']))->count()
            + Offer::when(isset($promotedIdsByType['offer']), fn ($query) => $query->whereNotIn('id', $promotedIdsByType['offer']))->count();
    }

    protected function mapPromotion(Promotion $promotion): ?array
    {
        $promotable = $promotion->promotable;

        if (! $promotable) {
            return null;
        }

        $type = strtolower(class_basename($promotion->promotable_type));

        return [
            'type' => $type,
            'data' => $promotable,
            'is_promoted' => true,
        ];
    }
}
