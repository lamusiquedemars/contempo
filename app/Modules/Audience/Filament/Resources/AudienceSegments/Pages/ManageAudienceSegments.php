<?php

namespace App\Modules\Audience\Filament\Resources\AudienceSegments\Pages;

use App\Modules\Audience\Filament\Resources\AudienceSegments\AudienceSegmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageAudienceSegments extends ManageRecords
{
    protected static string $resource = AudienceSegmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Créer un segment'),
        ];
    }
}
