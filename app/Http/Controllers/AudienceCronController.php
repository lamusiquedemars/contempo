<?php

namespace App\Http\Controllers;

use App\Modules\Audience\Actions\SendPendingSegmentMessages;
use App\Modules\SiteSettings\Models\SiteSetting;
use App\Support\Modules;
use Illuminate\Http\JsonResponse;

class AudienceCronController extends Controller
{
    public function __invoke(string $token): JsonResponse
    {
        abort_unless(Modules::enabled('audience'), 404);

        $settings = SiteSetting::current();
        $expectedToken = (string) $settings->audience_cron_token;

        abort_if($expectedToken === '' || ! hash_equals($expectedToken, $token), 404);

        if (! $settings->audience_cron_enabled) {
            return response()->json([
                'ok' => true,
                'status' => 'disabled',
                'message' => 'Audience cron disabled.',
            ]);
        }

        $stats = SendPendingSegmentMessages::run(
            limit: (int) ($settings->audience_send_limit ?: 25),
            maxSeconds: (int) ($settings->audience_send_max_seconds ?: 180),
            maxAttempts: (int) ($settings->audience_send_max_attempts ?: 3),
            domainLimitPerRun: (int) ($settings->audience_send_domain_limit ?: 3),
            excludedDomains: $settings->audienceExcludedDomains(),
        );

        $settings->forceFill([
            'audience_cron_last_ran_at' => now(),
            'audience_cron_last_result' => $stats,
        ])->save();

        return response()->json([
            'ok' => true,
            'status' => 'ran',
            'stats' => $stats,
        ]);
    }
}
