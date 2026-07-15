<?php

namespace App\Modules\SiteSettings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
        'contact_form_show_name',
        'contact_form_show_phone',
        'contact_form_show_subject',
        'contact_form_send_admin_email',
        'contact_form_send_confirmation_email',
        'audience_cron_enabled',
        'audience_cron_token',
        'audience_send_limit',
        'audience_send_domain_limit',
        'audience_send_max_seconds',
        'audience_send_max_attempts',
        'audience_excluded_domains',
        'audience_cron_last_ran_at',
        'audience_cron_last_result',
    ];

    protected function casts(): array
    {
        return [
            'social_links' => 'array',
            'contact_form_show_name' => 'boolean',
            'contact_form_show_phone' => 'boolean',
            'contact_form_show_subject' => 'boolean',
            'contact_form_send_admin_email' => 'boolean',
            'contact_form_send_confirmation_email' => 'boolean',
            'audience_cron_enabled' => 'boolean',
            'audience_cron_last_ran_at' => 'datetime',
            'audience_cron_last_result' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (SiteSetting $setting): void {
            if (! $setting->audience_cron_token) {
                $setting->audience_cron_token = Str::random(48);
            }
        });
    }

    public function getContactFormShowNameAttribute(?bool $value): bool
    {
        return $value ?? true;
    }

    public function getContactFormShowPhoneAttribute(?bool $value): bool
    {
        return $value ?? true;
    }

    public function getContactFormShowSubjectAttribute(?bool $value): bool
    {
        return $value ?? true;
    }

    public function getContactFormSendAdminEmailAttribute(?bool $value): bool
    {
        return $value ?? true;
    }

    public function getContactFormSendConfirmationEmailAttribute(?bool $value): bool
    {
        return $value ?? false;
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
            'contact_form_show_name' => true,
            'contact_form_show_phone' => true,
            'contact_form_show_subject' => true,
            'contact_form_send_admin_email' => true,
            'contact_form_send_confirmation_email' => false,
            'audience_cron_enabled' => true,
            'audience_cron_token' => Str::random(48),
            'audience_send_limit' => 25,
            'audience_send_domain_limit' => 3,
            'audience_send_max_seconds' => 180,
            'audience_send_max_attempts' => 3,
            'audience_excluded_domains' => 'hotmail.fr,hotmail.com,outlook.fr,outlook.com,live.fr,live.com,msn.com,free.fr,yahoo.fr,mailo.fr,mailo.com,edrmartin.fr',
        ]);
    }

    public function audienceExcludedDomains(): array
    {
        return collect(explode(',', (string) $this->audience_excluded_domains))
            ->map(fn (string $domain): string => strtolower(trim($domain)))
            ->filter()
            ->values()
            ->all();
    }

    public function audienceCronUrl(): string
    {
        return url('/maracuja/cron/audience/'.$this->audience_cron_token);
    }
}
