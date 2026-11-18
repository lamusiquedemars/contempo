<?php

namespace App\Console\Commands;

use App\Modules\Media\Services\MediaMigrationPlanner;
use Illuminate\Console\Command;

class MaracujaMediaMigrateCommand extends Command
{
    protected $signature = 'maracuja:media:migrate {--manifest= : Nom du manifeste} {--gallery : Prépare uniquement les images de galerie sans MediaAsset} {--apply : Applique un manifeste existant sans supprimer les sources} {--json : Affiche le plan complet}';

    protected $description = 'Prépare ou applique une migration de médias avec manifeste privé et sources conservées.';

    public function handle(MediaMigrationPlanner $planner): int
    {
        if ($this->option('apply')) {
            $manifest = (string) $this->option('manifest');
            if ($manifest === '') {
                $this->error('--manifest est obligatoire avec --apply.');

                return self::INVALID;
            }

            $plan = $planner->apply($manifest);
            $this->info('Manifeste appliqué — les sources historiques sont conservées.');
        } else {
            $plan = $this->option('gallery') ? $planner->planGalleryAssets() : $planner->plan();
            $manifest = $planner->writeManifest($plan, $this->option('manifest'));
            $this->info('Plan de migration créé — aucun média modifié.');
            $this->line('Manifeste privé : '.$manifest);
        }

        if ($this->option('json')) {
            $this->line((string) json_encode($plan, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        }

        return self::SUCCESS;
    }
}
