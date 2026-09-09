<?php

namespace App\Modules\CremonaBridge\Jobs;

use App\Modules\CremonaBridge\Models\CremonaDelivery;
use Illuminate\Support\Facades\Http;
use Throwable;

class SendCremonaDelivery
{
    public function __construct(public readonly int $deliveryId) {}

    public function handle(): void
    {
        $delivery = CremonaDelivery::query()->findOrFail($this->deliveryId);
        if ($delivery->status === 'delivered') {
            return;
        }

        $delivery->update(['status' => 'delivering', 'attempts' => $delivery->attempts + 1, 'last_attempt_at' => now(), 'last_error' => null]);

        try {
            $response = Http::acceptJson()->asJson()->withToken((string) config('maracuja.cremona.token'))->withHeader('Idempotency-Key', $delivery->idempotency_key)->timeout(15)->post((string) config('maracuja.cremona.endpoint'), $delivery->payload);
            if (in_array($response->status(), [401, 403, 409, 422], true)) {
                $delivery->update(['status' => 'failed', 'response_status' => $response->status(), 'last_error' => "Cremona a répondu HTTP {$response->status()}."]);

                return;
            }
            if (! $response->successful()) {
                $delivery->update(['status' => 'pending', 'response_status' => $response->status(), 'last_error' => "Cremona a répondu HTTP {$response->status()}."]);

                return;
            }

            $delivery->update(['status' => 'delivered', 'response_status' => $response->status(), 'remote_request_id' => $response->json('data.id'), 'delivered_at' => now(), 'last_error' => null]);
        } catch (Throwable $exception) {
            $delivery->update(['status' => 'pending', 'last_error' => mb_substr($exception->getMessage(), 0, 2000)]);
        }
    }
}
