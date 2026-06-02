<?php

namespace App\Modules\Audience\Actions;

use App\Modules\Audience\Mail\SegmentMessageMail;
use App\Modules\Audience\Models\AudienceContact;
use App\Modules\Audience\Models\SegmentMessage;
use App\Modules\Audience\Models\SegmentMessageDelivery;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendSegmentMessage
{
    public static function run(SegmentMessage $message): int
    {
        if ($message->isSent()) {
            return $message->recipients_count;
        }

        $contacts = $message->segment
            ->contacts()
            ->where('accepts_email', true)
            ->whereNull('unsubscribed_at')
            ->get();

        if ($contacts->isEmpty()) {
            return 0;
        }

        $sentCount = 0;

        $contacts->each(function (AudienceContact $contact) use ($message, &$sentCount): void {
            try {
                Mail::to($contact->email)->send(new SegmentMessageMail($message, $contact));

                SegmentMessageDelivery::query()->create([
                    'segment_message_id' => $message->id,
                    'audience_contact_id' => $contact->id,
                    'email' => $contact->email,
                    'status' => 'sent',
                    'sent_at' => now(),
                ]);

                $contact->forceFill([
                    'last_contacted_at' => now(),
                ])->save();

                $sentCount++;
            } catch (Throwable $exception) {
                SegmentMessageDelivery::query()->create([
                    'segment_message_id' => $message->id,
                    'audience_contact_id' => $contact->id,
                    'email' => $contact->email,
                    'status' => 'failed',
                    'error_message' => $exception->getMessage(),
                ]);
            }
        });

        $message->forceFill([
            'status' => 'sent',
            'recipients_count' => $sentCount,
            'sent_at' => now(),
        ])->save();

        return $sentCount;
    }
}
