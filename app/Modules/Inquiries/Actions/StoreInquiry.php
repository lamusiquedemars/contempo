<?php

namespace App\Modules\Inquiries\Actions;

use App\Modules\ContactForm\Data\ContactMessage;
use App\Modules\Inquiries\Enums\InquiryStatus;
use App\Modules\Inquiries\Models\Inquiry;

class StoreInquiry
{
    public static function run(ContactMessage $message): Inquiry
    {
        return Inquiry::query()->create([
            ...$message->toArray(),
            'status' => InquiryStatus::New,
        ]);
    }
}
