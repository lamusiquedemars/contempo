<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaFiles
{
    /** @return array<string, string> */
    public static function options(string $directory): array
    {
        return collect(Storage::disk('public')->files($directory))
            ->filter(fn (string $path): bool => self::isImagePath($path))
            ->sort()
            ->mapWithKeys(function (string $path): array {
                $dimensions = self::dimensions($path);
                $label = basename($path);

                if ($dimensions) {
                    $label .= " ({$dimensions['width']} x {$dimensions['height']})";
                }

                return [$path => $label];
            })
            ->all();
    }

    /** @return array<string, string> */
    public static function publicOptions(string $directory = 'media'): array
    {
        $directory = trim($directory, '/');
        $root = public_path($directory);

        if (! is_dir($root)) {
            return [];
        }

        return collect(glob($root.'/*') ?: [])
            ->filter(fn (string $path): bool => is_file($path) && self::isImagePath($path))
            ->sort()
            ->mapWithKeys(function (string $path) use ($directory): array {
                $publicPath = $directory.'/'.basename($path);
                $dimensions = self::dimensions($publicPath);
                $label = basename($path);

                if ($dimensions) {
                    $label .= " ({$dimensions['width']} x {$dimensions['height']})";
                }

                return [$publicPath => $label];
            })
            ->all();
    }

    public static function isAllowed(string $path, string $directory): bool
    {
        return self::isStoragePath($path)
            && Str::startsWith($path, trim($directory, '/').'/')
            && Storage::disk('public')->exists($path)
            && self::isImagePath($path);
    }

    public static function isPublicAllowed(string $path, string $directory): bool
    {
        $path = ltrim($path, '/');
        $directory = trim($directory, '/');

        return Str::startsWith($path, $directory.'/')
            && is_file(public_path($path))
            && self::isImagePath($path);
    }

    public static function normalizePublicPath(mixed $state): mixed
    {
        if (is_array($state)) {
            return collect($state)
                ->map(fn (mixed $path): mixed => self::normalizePublicPath($path))
                ->filter()
                ->values()
                ->all();
        }

        if (blank($state) || ! is_string($state)) {
            return $state;
        }

        if (Str::startsWith($state, ['http://', 'https://', '/'])) {
            return $state;
        }

        return '/'.ltrim($state, '/');
    }

    public static function publicDiskPath(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        return ltrim($path, '/');
    }

    public static function url(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://', '/'])) {
            return $path;
        }

        if (Str::startsWith($path, 'media/')) {
            return '/'.$path;
        }

        return Storage::disk('public')->url($path);
    }

    /** @return array{width: int, height: int}|null */
    public static function dimensions(?string $path): ?array
    {
        if (blank($path)) {
            return null;
        }

        $absolutePath = self::absolutePath($path);

        if (! $absolutePath || ! is_file($absolutePath)) {
            return null;
        }

        $size = @getimagesize($absolutePath);

        if (! is_array($size)) {
            return null;
        }

        return [
            'width' => (int) $size[0],
            'height' => (int) $size[1],
        ];
    }

    public static function isStoragePath(string $path): bool
    {
        return ! Str::startsWith($path, ['http://', 'https://', '/']);
    }

    private static function absolutePath(string $path): ?string
    {
        if (Str::startsWith($path, '/')) {
            return public_path(ltrim($path, '/'));
        }

        if (Str::startsWith($path, 'media/')) {
            return public_path($path);
        }

        return Storage::disk('public')->path($path);
    }

    private static function isImagePath(string $path): bool
    {
        return Str::of($path)->lower()->endsWith(['.jpg', '.jpeg', '.png', '.webp', '.gif', '.svg']);
    }
}
