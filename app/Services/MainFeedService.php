<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Business;
use App\Models\Feed;
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
        $modelConfig = [
            'article' => [Article::class, ['categories', 'images']],
            'todo' => [Todo::class, []],
            'business' => [Business::class, ['categories']],
            'offer' => [Offer::class, ['categories', 'images']],
        ];

        $feedItems = Feed::query()
            ->where(function ($query) use ($promotedIdsByType) {
                foreach (array_keys($promotedIdsByType + [
                    'article' => [],
                    'todo' => [],
                    'business' => [],
                    'offer' => [],
                ]) as $type) {
                    $query->orWhere(function ($query) use ($type, $promotedIdsByType) {
                        $query->where('type', $type)
                            ->when(isset($promotedIdsByType[$type]), fn ($query) => $query->whereNotIn('item_id', $promotedIdsByType[$type]));
                    });
                }
            })
            ->latest('created_at')
            ->limit($limit)
            ->get();

        $itemsByType = collect($modelConfig)->mapWithKeys(function (array $config, string $type) use ($feedItems) {
            [$model, $relations] = $config;
            $ids = $feedItems->where('type', $type)->pluck('item_id');

            return [$type => $model::with($relations)->whereIn('id', $ids)->get()->keyBy('id')];
        });

        return $feedItems->map(function (Feed $feedItem) use ($itemsByType) {
            $item = $itemsByType[$feedItem->type]->get($feedItem->item_id);

            return $item ? ['type' => $feedItem->type, 'data' => $item] : null;
        })->filter()->values();
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
