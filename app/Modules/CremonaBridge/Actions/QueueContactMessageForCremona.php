<?php

namespace App\Modules\CremonaBridge\Actions;

use App\Modules\ContactForm\Data\ContactMessage;
use App\Modules\CremonaBridge\Jobs\SendCremonaDelivery;
use App\Modules\CremonaBridge\Models\CremonaDelivery;

class QueueContactMessageForCremona
{
    public static function run(ContactMessage $message): CremonaDelivery
    {
        $delivery = CremonaDelivery::query()->create([
            'idempotency_key' => 'contempo:contact:'.str()->ulid(),
            'status' => 'pending',
            'payload' => [
                'source' => ['channel' => 'website', 'name' => 'contempo-cms', 'site_reference' => config('maracuja.cremona.site_reference'), 'form_reference' => 'contact'],
                'contact' => ['name' => $message->name, 'email' => $message->email, 'phone' => $message->phone],
                'request' => ['subject' => $message->subject, 'message' => $message->message],
            ],
        ]);
        (new SendCremonaDelivery($delivery->getKey()))->handle();

        return $delivery;
    }
}
