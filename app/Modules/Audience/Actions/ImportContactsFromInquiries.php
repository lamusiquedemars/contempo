<?php

namespace App\Modules\Audience\Actions;

use App\Modules\Audience\Models\AudienceContact;
use App\Modules\Inquiries\Models\Inquiry;
use Illuminate\Support\Facades\Schema as SchemaFacade;

class ImportContactsFromInquiries
{
    public static function run(): array
    {
        if (! class_exists(Inquiry::class) || ! SchemaFacade::hasTable('inquiries')) {
            return ['created' => 0, 'existing' => 0];
        }

        $created = 0;
        $existing = 0;

        Inquiry::query()
            ->select(['name', 'email'])
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->orderBy('id')
            ->get()
            ->unique(fn (Inquiry $inquiry) => mb_strtolower(trim($inquiry->email)))
            ->each(function (Inquiry $inquiry) use (&$created, &$existing): void {
                $email = mb_strtolower(trim($inquiry->email));
                $name = trim((string) $inquiry->name);

                $alreadyExists = AudienceContact::query()
                    ->whereRaw('lower(email) = ?', [$email])
                    ->exists();

                if ($alreadyExists) {
                    $existing++;

                    return;
                }

                AudienceContact::query()->create([
                    'first_name' => $name !== '' ? $name : null,
                    'email' => $email,
                    'accepts_email' => true,
                    'notes' => 'Importé depuis les demandes du formulaire.',
                ]);

                $created++;
            });

        return ['created' => $created, 'existing' => $existing];
    }
}
