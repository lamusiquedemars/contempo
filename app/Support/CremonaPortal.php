<?php

namespace App\Support;

final class CremonaPortal
{
    /** @return array<int, array{key: string, label: string, url: string}> */
    public static function navigation(): array
    {
        $items = session('cremona_portal.navigation', []);

        return is_array($items) ? $items : [];
    }

    public static function has(string $key): bool
    {
        return collect(self::navigation())->contains(fn (mixed $item): bool => is_array($item) && ($item['key'] ?? null) === $key);
    }
}
