<?php

namespace App\Modules\Pages\Models;

use App\Support\MediaFiles;
use App\Support\Modules;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Maracuja\SiteCore\Media\Concerns\TracksMediaUsages;
use Maracuja\SiteCore\Media\Models\MediaAsset;

class Page extends Model
{
    use TracksMediaUsages;
    public const TYPE_SYSTEM = 'system';

    public const TYPE_TEXT = 'text';

    public const TYPE_MODULE = 'module';

    protected $fillable = [
        'title',
        'slug',
        'template',
        'type',
        'excerpt',
        'hero_title',
        'hero_subtitle',
        'hero_image_path',
        'hero_media_id',
        'content',
        'seo_title',
        'seo_description',
        'is_published',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function heroMedia(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'hero_media_id');
    }

    public function heroImageUrl(): ?string
    {
        return $this->trackedMedia('heroMedia', $this->hero_media_id)?->url() ?? MediaFiles::url($this->hero_image_path);
    }

    /** @return array<int, string> */
    public function heroImageSources(): array
    {
        $media = $this->trackedMedia('heroMedia', $this->hero_media_id);

        if (! $media?->isImage()) {
            return [];
        }

        $variants = collect($media->variants ?? [])
            ->filter(fn (array $variant): bool => filled($variant['path'] ?? null) && filled($variant['width'] ?? null))
            ->mapWithKeys(fn (array $variant): array => [(int) $variant['width'] => Storage::disk($media->disk)->url($variant['path'])])
            ->sortKeys();

        return collect([640, 960, 1440, 1920])
            ->mapWithKeys(function (int $targetWidth) use ($variants): array {
                $url = $variants->first(fn (string $url, int $width): bool => $width >= $targetWidth) ?? $variants->last();

                return $url ? [$targetWidth => $url] : [];
            })
            ->all();
    }

    protected function mediaUsageReferences(): array
    {
        return [
            ['media_asset_id' => $this->hero_media_id, 'field' => 'hero_media_id'],
            ...$this->mediaUsageReferencesFromHtml($this->content, 'content'),
        ];
    }

    public function isSystem(): bool
    {
        return $this->type === self::TYPE_SYSTEM;
    }

    public function isText(): bool
    {
        return $this->type === self::TYPE_TEXT;
    }

    public function isModule(): bool
    {
        return $this->type === self::TYPE_MODULE;
    }

    public function publicUrl(): ?string
    {
        return match ($this->slug) {
            'accueil' => route('home'),
            'actualites' => route('news.index'),
            'contact' => Modules::enabled('contact_form') ? route('contact') : null,
            default => route('pages.show', $this->slug),
        };
    }
}
