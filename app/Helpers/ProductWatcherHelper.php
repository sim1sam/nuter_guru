<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ProductWatcherHelper
{
    private const TTL_SECONDS = 120;

    public static function register(int $productId, ?string $visitorId = null): string
    {
        $visitorId = $visitorId ?: self::visitorId();
        $watchers = self::activeWatchers($productId);
        $watchers[$visitorId] = time();

        Cache::put(self::cacheKey($productId), $watchers, self::TTL_SECONDS + 30);

        return $visitorId;
    }

    public static function count(int $productId): int
    {
        return max(1, count(self::activeWatchers($productId, true)));
    }

    public static function visitorId(): string
    {
        if (! session()->has('product_watcher_id')) {
            session()->put('product_watcher_id', (string) Str::uuid());
        }

        return (string) session()->get('product_watcher_id');
    }

    private static function activeWatchers(int $productId, bool $persistCleanup = false): array
    {
        $watchers = Cache::get(self::cacheKey($productId), []);
        $now = time();

        $watchers = array_filter(
            $watchers,
            static fn ($timestamp) => ($now - (int) $timestamp) < self::TTL_SECONDS
        );

        if ($persistCleanup && ! empty($watchers)) {
            Cache::put(self::cacheKey($productId), $watchers, self::TTL_SECONDS + 30);
        }

        return $watchers;
    }

    private static function cacheKey(int $productId): string
    {
        return 'product_watchers:' . $productId;
    }
}
