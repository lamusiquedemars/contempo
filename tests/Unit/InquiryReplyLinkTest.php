<?php

namespace Tests\Unit;

use App\Modules\Inquiries\Models\Inquiry;
use App\Modules\Inquiries\Support\InquiryReplyLink;
use Tests\TestCase;

class InquiryReplyLinkTest extends TestCase
{
    public function test_it_builds_a_mailto_with_subject_and_body(): void
    {
        $inquiry = new Inquiry([
            'name' => 'Claire',
            'email' => 'claire@example.test',
            'subject' => 'Location violon',
            'message' => 'Bonjour, je souhaite une location.',
        ]);

        $url = InquiryReplyLink::make($inquiry);

        $this->assertStringStartsWith('mailto:claire@example.test?', $url);
        $this->assertStringContainsString(rawurlencode('Re: Location violon'), $url);
        $this->assertStringContainsString(rawurlencode('Bonjour Claire'), $url);
        $this->assertStringContainsString(rawurlencode('--- Message initial ---'), $url);
    }

    public function test_it_uses_default_subject_when_empty(): void
    {
        $inquiry = new Inquiry([
            'name' => 'Ivo',
            'email' => 'ivo@example.test',
            'message' => 'Message simple.',
        ]);

        $url = InquiryReplyLink::make($inquiry);

        $this->assertStringContainsString(rawurlencode('Re: Votre demande'), $url);
    }
}
