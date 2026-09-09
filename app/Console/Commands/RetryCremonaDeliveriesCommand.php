<?php

namespace App\Console\Commands;

use App\Modules\CremonaBridge\Jobs\SendCremonaDelivery;
use App\Modules\CremonaBridge\Models\CremonaDelivery;
use Illuminate\Console\Command;

class RetryCremonaDeliveriesCommand extends Command
{
    protected $signature = 'cremona:retry-deliveries {--limit=50 : Nombre maximal de transmissions à relancer}';

    protected $description = 'Relance les transmissions de formulaires en attente vers Cremona.';

    public function handle(): int
    {
        $deliveries = CremonaDelivery::query()->where('status', 'pending')->orderBy('last_attempt_at')->limit((int) $this->option('limit'))->get();
        foreach ($deliveries as $delivery) {
            (new SendCremonaDelivery($delivery->getKey()))->handle();
        }
        $this->components->info("{$deliveries->count()} transmission(s) relancée(s).");

        return self::SUCCESS;
    }
}
