<?php

namespace App\Modules\Inquiries\Filament\Resources\Inquiries\Pages;

use App\Modules\Inquiries\Filament\Resources\Inquiries\InquiryResource;
use Filament\Resources\Pages\ManageRecords;

class ManageInquiries extends ManageRecords
{
    protected static string $resource = InquiryResource::class;
}
