<?php

namespace App\Providers\Filament;

use App\Filament\Auth\CremonaSsoLogin;
use App\Filament\Widgets\AdminOverview;
use App\Filament\Widgets\AdminWelcome;
use App\Modules\SiteSettings\Models\SiteSetting;
use App\Support\Modules;
use Filament\Auth\Pages\Login;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Assets\Js;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Maracuja\SiteCore\Gallery\Filament\Resources\Galleries\GalleryResource;
use Maracuja\SiteCore\Audience\Filament\Pages\AudienceHome;
use Maracuja\SiteCore\Media\Filament\Resources\MediaAssets\MediaAssetResource;
use Maracuja\SiteCore\ProfessionalWorkspace\Filament\ProfessionalWorkspacePlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $panel = $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(config('cremona-portal.enabled') ? CremonaSsoLogin::class : Login::class)
            ->profile()
            ->brandName(fn (): string => Schema::hasTable('site_settings')
                ? SiteSetting::query()->value('site_name') ?: config('maracuja.product_name', 'Maracuja CMS')
                : config('maracuja.product_name', 'Maracuja CMS'))
            ->brandLogo(fn (): ?string => Schema::hasTable('site_settings')
                ? SiteSetting::query()->first()?->logoUrl()
                : null)
            ->brandLogoHeight('2.5rem')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->assets([
                Js::make('safari-livewire-upload-fix', resource_path('js/filament/safari-livewire-upload-fix.js')),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                AdminWelcome::class,
                AdminOverview::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);

        if (class_exists(ProfessionalWorkspacePlugin::class)) {
            $panel->plugin(
                (new ProfessionalWorkspacePlugin)
                    ->entryRoute('cremona-portal')
                    ->defaultLabel('Pilotage de l’activité'),
            );
        }

        if (Modules::enabled('inquiries') && is_dir(app_path('Modules/Inquiries/Filament/Resources'))) {
            $panel->discoverResources(
                in: app_path('Modules/Inquiries/Filament/Resources'),
                for: 'App\Modules\Inquiries\Filament\Resources',
            );
        }

        if (Modules::enabled('audience') && class_exists(AudienceHome::class)) {
            $panel->discoverResources(
                in: base_path('vendor/maracuja-digital/site-core-audience/src/Filament/Resources'),
                for: 'Maracuja\SiteCore\Audience\Filament\Resources',
            );
            $panel->pages([AudienceHome::class]);
        }

        if (Modules::enabled('brevo') && is_dir(base_path('vendor/maracuja-digital/site-core-brevo/src/Filament/Resources'))) {
            $panel->discoverResources(
                in: base_path('vendor/maracuja-digital/site-core-brevo/src/Filament/Resources'),
                for: 'Maracuja\SiteCore\Brevo\Filament\Resources',
            );
        }

        $panel->resources([
            MediaAssetResource::class,
            GalleryResource::class,
        ]);

        return $panel;
    }
}
