<?php

namespace App\Modules\Inquiries\Support;

use App\Modules\Inquiries\Models\Inquiry;

class InquiryReplyLink
{
    public static function make(Inquiry $inquiry): string
    {
        $subject = 'Re: '.($inquiry->subject ?: 'Votre demande');

        $bodyLines = [
            'Bonjour '.($inquiry->name ?: ''),
            '',
            'Merci pour votre message.',
            '',
            'Bien cordialement,',
        ];

        if ($inquiry->message) {
            $bodyLines[] = '';
            $bodyLines[] = '--- Message initial ---';
            $bodyLines[] = $inquiry->message;
        }

        $body = implode("\n", $bodyLines);

        return 'mailto:'.$inquiry->email
            .'?subject='.rawurlencode($subject)
            .'&body='.rawurlencode($body);
    }
}
