<?php

namespace Tests\Unit;

use App\Modules\Audience\Actions\SendSegmentMessage;
use App\Modules\Audience\Mail\SegmentMessageMail;
use App\Modules\Audience\Models\AudienceContact;
use App\Modules\Audience\Models\AudienceSegment;
use App\Modules\Audience\Models\SegmentMessage;
use App\Modules\Audience\Models\SegmentMessageDelivery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SendSegmentMessageTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_sends_a_segment_message_to_eligible_contacts_only(): void
    {
        Mail::fake();

        $segment = AudienceSegment::query()->create([
            'name' => 'Locations violon',
        ]);

        $eligible = AudienceContact::query()->create([
            'first_name' => 'Ivo',
            'email' => 'ivo@example.test',
            'accepts_email' => true,
        ]);

        $refused = AudienceContact::query()->create([
            'first_name' => 'Ana',
            'email' => 'ana@example.test',
            'accepts_email' => false,
        ]);

        $unsubscribed = AudienceContact::query()->create([
            'first_name' => 'Lina',
            'email' => 'lina@example.test',
            'accepts_email' => true,
            'unsubscribed_at' => now(),
        ]);

        $segment->contacts()->attach([$eligible->id, $refused->id, $unsubscribed->id]);

        $message = SegmentMessage::query()->create([
            'audience_segment_id' => $segment->id,
            'subject' => 'Rappel location',
            'body' => 'Votre location arrive à échéance.',
        ]);

        $sentCount = SendSegmentMessage::run($message);

        $this->assertSame(1, $sentCount);
        $this->assertSame('sent', $message->refresh()->status);
        $this->assertSame(1, $message->recipients_count);
        $this->assertNotNull($message->sent_at);
        $this->assertNotNull($eligible->refresh()->last_contacted_at);
        $this->assertNull($refused->refresh()->last_contacted_at);
        $this->assertNull($unsubscribed->refresh()->last_contacted_at);
        $this->assertSame(1, SegmentMessageDelivery::query()->count());
        $this->assertSame('sent', SegmentMessageDelivery::query()->first()->status);

        Mail::assertSent(SegmentMessageMail::class, 1);
    }

    public function test_it_does_not_send_the_same_segment_message_twice(): void
    {
        Mail::fake();

        $segment = AudienceSegment::query()->create([
            'name' => 'Clients atelier',
        ]);

        $contact = AudienceContact::query()->create([
            'email' => 'ivo@example.test',
            'accepts_email' => true,
        ]);

        $segment->contacts()->attach($contact);

        $message = SegmentMessage::query()->create([
            'audience_segment_id' => $segment->id,
            'subject' => 'Information atelier',
            'body' => 'Message simple.',
        ]);

        SendSegmentMessage::run($message);
        SendSegmentMessage::run($message->refresh());

        Mail::assertSent(SegmentMessageMail::class, 1);
    }

    public function test_it_keeps_message_as_draft_when_no_eligible_recipient_exists(): void
    {
        Mail::fake();

        $segment = AudienceSegment::query()->create([
            'name' => 'Segment vide',
        ]);

        $contact = AudienceContact::query()->create([
            'email' => 'ana@example.test',
            'accepts_email' => false,
        ]);

        $segment->contacts()->attach($contact);

        $message = SegmentMessage::query()->create([
            'audience_segment_id' => $segment->id,
            'subject' => 'Information',
            'body' => 'Message sans destinataire éligible.',
        ]);

        $sentCount = SendSegmentMessage::run($message);

        $this->assertSame(0, $sentCount);
        $this->assertSame('draft', $message->refresh()->status);
        $this->assertSame(0, $message->refresh()->recipients_count);
        $this->assertNull($message->sent_at);
        $this->assertSame(0, SegmentMessageDelivery::query()->count());
        Mail::assertNothingSent();
    }
}
