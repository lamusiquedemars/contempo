<?php

namespace App\Providers;

use App\Models\User;
use App\Modules\Audience\Models\AudienceBrevoSetting;
use App\Modules\Media\Models\MediaAsset;
use App\Modules\Media\Policies\MediaAssetPolicy;
use App\Modules\SiteSettings\Models\SiteSetting;
use App\Support\ContentPermissions;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use RuntimeException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
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
