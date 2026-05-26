<?php

namespace App\Modules\SiteSettings\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'site_name',
        'baseline',
        'default_seo_title',
        'default_seo_description',
        'contact_email',
        'phone',
        'address',
        'logo_path',
        'favicon_path',
        'default_og_image_path',
        'social_links',
    ];

    protected function casts(): array
    {
        return [
            'social_links' => 'array',
        ];
    }

    public static function current(): self
    {
        return static::query()->firstOrCreate([], [
            'site_name' => 'Maracuja CMS',
            'baseline' => 'Site vitrine administrable, sobre et sur mesure.',
            'default_seo_title' => 'Maracuja CMS',
            'default_seo_description' => 'Un starter Laravel + Filament pour sites vitrines administrables.',
            'default_og_image_path' => '/demo/theme-system.svg',
            'contact_email' => 'contact@example.test',
        ]);
    }
}
