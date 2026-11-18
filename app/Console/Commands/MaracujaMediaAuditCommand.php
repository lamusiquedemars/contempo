<?php

namespace App\Console\Commands;

use App\Modules\Media\Services\MediaAuditService;
use Illuminate\Console\Command;

class MaracujaMediaAuditCommand extends Command
{
    protected $signature = 'maracuja:media:audit {--json : Retourne le rapport complet au format JSON} {--no-database : Ignore temporairement les références en base de données}';

    protected $description = 'Audite les stockages et références médias sans modifier aucun fichier.';

    public function handle(MediaAuditService $auditService): int
    {
        $report = $auditService->audit(! (bool) $this->option('no-database'));

        if ($this->option('json')) {
            $this->line((string) json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

            return $report['summary']['anomalies'] > 0 ? self::FAILURE : self::SUCCESS;
        }

        $this->table(['Mesure', 'Total'], [
            ['Fichiers physiques', $report['summary']['files']],
            ['Références détectées', $report['summary']['references']],
            ['Anomalies', $report['summary']['anomalies']],
        ]);

        return $report['summary']['anomalies'] > 0 ? self::FAILURE : self::SUCCESS;
    }
}
