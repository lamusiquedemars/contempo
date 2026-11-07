<?php

namespace App\Providers\Filament;

use App\Filament\Auth\CremonaSsoLogin;
use App\Filament\Widgets\AdminOverview;
use App\Filament\Widgets\AdminWelcome;
use App\Modules\SiteSettings\Models\SiteSetting;
use App\Support\CremonaPortal;
use App\Support\Modules;
use Filament\Auth\Pages\Login;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
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
            ->navigationItems([
                NavigationItem::make('Instruments')->group('Gestion de l’activité')->icon('heroicon-o-musical-note')->sort(10)->url(fn (): string => route('cremona-portal', ['item' => 'instruments']))->visible(fn (): bool => CremonaPortal::has('instruments')),
                NavigationItem::make('Locations')->group('Gestion de l’activité')->icon('heroicon-o-key')->sort(20)->url(fn (): string => route('cremona-portal', ['item' => 'rentals']))->visible(fn (): bool => CremonaPortal::has('rentals')),
                NavigationItem::make('Dossiers atelier')->group('Gestion de l’activité')->icon('heroicon-o-wrench-screwdriver')->sort(30)->url(fn (): string => route('cremona-portal', ['item' => 'workshop_orders']))->visible(fn (): bool => CremonaPortal::has('workshop_orders')),
                NavigationItem::make('Facturation')->group('Gestion de l’activité')->icon('heroicon-o-document-currency-euro')->sort(40)->url(fn (): string => route('cremona-portal', ['item' => 'invoices']))->visible(fn (): bool => CremonaPortal::has('invoices')),
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

        if (Modules::enabled('inquiries') && is_dir(app_path('Modules/Inquiries/Filament/Resources'))) {
            $panel->discoverResources(
                in: app_path('Modules/Inquiries/Filament/Resources'),
                for: 'App\Modules\Inquiries\Filament\Resources',
            );
        }

        if (Modules::enabled('audience') && is_dir(app_path('Modules/Audience/Filament/Resources'))) {
            $panel->discoverResources(
                in: app_path('Modules/Audience/Filament/Resources'),
                for: 'App\Modules\Audience\Filament\Resources',
            );
        }

        if (is_dir(app_path('Modules/Media/Filament/Resources'))) {
            $panel->discoverResources(
                in: app_path('Modules/Media/Filament/Resources'),
                for: 'App\\Modules\\Media\\Filament\\Resources',
            );
        }

        return $panel;
    }
}
