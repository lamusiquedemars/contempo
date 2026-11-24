<?php

namespace App\Providers;

use App\Models\User;
use App\Modules\Audience\Models\AudienceBrevoSetting;
use App\Modules\Media\Policies\MediaAssetPolicy;
use App\Modules\SiteSettings\Models\SiteSetting;
use App\Support\ContentPermissions;
use App\Support\SiteFoundationAccessGate;
use App\Support\SiteMediaAccessGate;
use App\Support\SiteModuleAvailability;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Maracuja\SiteCore\Foundation\Contracts\FoundationAccessGate;
use Maracuja\SiteCore\Foundation\Contracts\ModuleAvailability;
use Maracuja\SiteCore\Media\Contracts\MediaAccessGate;
use Maracuja\SiteCore\Media\Models\MediaAsset;
use RuntimeException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // LWS livre ces deux paquets dans vendor/, mais son registre Composer
        // historique ne les indexe pas toujours. Ce pont ne s'active que pour
        // les classes réellement présentes et conserve le chargement Composer
        // habituel partout ailleurs.
        spl_autoload_register(static function (string $class): void {
            foreach ([
                'Maracuja\\SiteCore\\Foundation\\' => base_path('vendor/maracuja-digital/site-core-foundation/src/'),
                'Maracuja\\SiteCore\\Audience\\' => base_path('vendor/maracuja-digital/site-core-audience/src/'),
                'Maracuja\\SiteCore\\Gallery\\' => base_path('vendor/maracuja-digital/site-core-gallery/src/'),
                'Maracuja\\SiteCore\\ProfessionalWorkspace\\' => base_path('vendor/maracuja-digital/site-core-professional-workspace/src/'),
            ] as $prefix => $directory) {
                if (! str_starts_with($class, $prefix)) {
                    continue;
                }

                $file = $directory.str_replace('\\', '/', substr($class, strlen($prefix))).'.php';

                if (is_file($file)) {
                    require_once $file;
                }

                return;
            }
        });

        $this->app->singleton(FoundationAccessGate::class, SiteFoundationAccessGate::class);
        $this->app->singleton(ModuleAvailability::class, SiteModuleAvailability::class);
        $this->app->singleton(MediaAccessGate::class, SiteMediaAccessGate::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->guardTestingDatabase();

        $moduleMigrationPaths = [
            app_path('Modules/Inquiries/database/migrations'),
            app_path('Modules/Audience/database/migrations'),
            app_path('Modules/CremonaBridge/database/migrations'),
            app_path('Modules/InstrumentProjection/database/migrations'),
            app_path('Modules/Media/database/migrations'),
        ];

        foreach ($moduleMigrationPaths as $path) {
            if (is_dir($path)) {
                $this->loadMigrationsFrom($path);
            }
        }

        Gate::policy(MediaAsset::class, MediaAssetPolicy::class);

        Gate::before(function (User $user, string $ability, array $arguments): ?bool {
            if ($user->isAdministrator()) {
                return true;
            }

            $subject = $arguments[0] ?? null;
            $subjectClass = is_object($subject) ? $subject::class : $subject;

            if (in_array($subjectClass, [User::class, SiteSetting::class, AudienceBrevoSetting::class], true)
                || ! ContentPermissions::allows($user, $subjectClass)) {
                return false;
            }

            if (! $user->canEditContent() && ! in_array($ability, ['viewAny', 'view'], true)) {
                return false;
            }

            return $subjectClass && Gate::getPolicyFor($subjectClass) ? null : true;
        });
    }

    private function guardTestingDatabase(): void
    {
        if (! $this->app->environment('testing')) {
            return;
        }

        $connection = (string) config('database.default');
        $database = (string) config("database.connections.{$connection}.database");

        if ($database === ':memory:' || str_ends_with($database, '_testing')) {
            return;
        }

        throw new RuntimeException(
            "Tests blocked: database [{$database}] is not a dedicated testing database. "
            .'Use a database name ending in [_testing].'
        );
    }
}
