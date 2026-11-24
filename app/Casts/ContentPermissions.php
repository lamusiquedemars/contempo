<?php

declare(strict_types=1);

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * Reads both the standard JSON permission list and legacy double-encoded data.
 */
final class ContentPermissions implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): ?array
    {
        return self::normalize($value);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): array
    {
        $permissions = self::normalize($value);

        return [$key => $permissions === null ? null : json_encode($permissions, JSON_THROW_ON_ERROR)];
    }

    /** @return array<int, string>|null */
    private static function normalize(mixed $value): ?array
    {
        if ($value === null) {
            return null;
        }

        for ($attempt = 0; $attempt < 2 && is_string($value); $attempt++) {
            $value = json_decode($value, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return [];
            }
        }

        if (! is_array($value)) {
            return [];
        }

        return array_values(array_unique(array_filter($value, 'is_string')));
    }
}
