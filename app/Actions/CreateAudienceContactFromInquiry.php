<?php

declare(strict_types=1);

namespace App\Actions;

use App\Modules\Inquiries\Models\Inquiry;
use Maracuja\SiteCore\Audience\Models\AudienceContact;

final class CreateAudienceContactFromInquiry
{
    /** @return array{contact: AudienceContact, created: bool} */
    public static function run(Inquiry $inquiry): array
    {
        $email = mb_strtolower(trim((string) $inquiry->email));

        $existing = AudienceContact::query()
            ->whereRaw('lower(email) = ?', [$email])
            ->first();

        if ($existing !== null) {
            return ['contact' => $existing, 'created' => false];
        }

        $contact = AudienceContact::query()->create([
            'first_name' => trim((string) $inquiry->name) ?: null,
            'email' => $email,
            'accepts_email' => true,
            'consent_source' => 'inquiry',
            'notes' => "Créé depuis la demande #{$inquiry->id}.",
        ]);

        return ['contact' => $contact, 'created' => true];
    }
}
