<?php

namespace Tests\Feature;

use App\Modules\Audience\Actions\QueueSegmentMessage;
use App\Modules\Audience\Mail\SegmentMessageMail;
use App\Modules\Audience\Models\AudienceContact;
use App\Modules\Audience\Models\AudienceSegment;
use App\Modules\Audience\Models\SegmentMessage;
use App\Modules\Audience\Models\SegmentMessageDelivery;
use App\Modules\SiteSettings\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AudienceCronTest extends TestCase
{
    use RefreshDatabase;

    public function test_audience_cron_runs_pending_deliveries_with_site_settings(): void
    {
        Mail::fake();

        config(['maracuja.modules.audience' => true]);

        $settings = SiteSetting::current();
        $settings->forceFill([
            'audience_cron_token' => 'valid-token',
            'audience_cron_enabled' => true,
            'audience_send_limit' => 2,
            'audience_send_domain_limit' => 1,
            'audience_send_max_seconds' => 180,
            'audience_send_max_attempts' => 3,
            'audience_excluded_domains' => 'blocked.test',
        ])->save();

        $segment = AudienceSegment::query()->create([
            'name' => 'Clients',
        ]);

        $contacts = collect([
            'one@example.test',
            'two@example.test',
            'two@other.test',
            'three@blocked.test',
        ])->map(fn (string $email): AudienceContact => AudienceContact::query()->create([
            'email' => $email,
            'accepts_email' => true,
        ]));

        $segment->contacts()->attach($contacts->pluck('id'));

        $message = SegmentMessage::query()->create([
            'audience_segment_id' => $segment->id,
            'subject' => 'Info',
            'body' => 'Bonjour.',
        ]);

        QueueSegmentMessage::run($message);

        $this->get('/maracuja/cron/audience/wrong-token')
            ->assertNotFound();

        $this->get('/maracuja/cron/audience/valid-token')
            ->assertOk()
            ->assertJsonPath('stats.sent', 2)
            ->assertJsonPath('stats.failed', 0);

        $this->assertSame(2, SegmentMessageDelivery::query()->where('status', SegmentMessageDelivery::STATUS_SENT)->count());
        $this->assertSame(1, SegmentMessageDelivery::query()->where('status', SegmentMessageDelivery::STATUS_PENDING)->count());
        $this->assertSame(1, SegmentMessageDelivery::query()->where('status', SegmentMessageDelivery::STATUS_SKIPPED)->count());
        $this->assertSame(1, SegmentMessageDelivery::query()->where('email', 'one@example.test')->where('status', SegmentMessageDelivery::STATUS_SENT)->count());
        $this->assertSame(1, SegmentMessageDelivery::query()->where('email', 'two@other.test')->where('status', SegmentMessageDelivery::STATUS_SENT)->count());
        $this->assertNotNull($settings->refresh()->audience_cron_last_ran_at);
        $this->assertSame(2, $settings->audience_cron_last_result['sent']);

        Mail::assertSent(SegmentMessageMail::class, 2);
    }

    public function test_disabled_audience_cron_does_not_send_messages(): void
    {
        Mail::fake();

        config(['maracuja.modules.audience' => true]);

        $settings = SiteSetting::current();
        $settings->forceFill([
            'audience_cron_token' => 'valid-token',
            'audience_cron_enabled' => false,
        ])->save();

        $this->get('/maracuja/cron/audience/valid-token')
            ->assertOk()
            ->assertJsonPath('status', 'disabled');

        Mail::assertNothingSent();
    }
}
