<?php

namespace App\Modules\Audience\Filament\Resources\SegmentMessages\Pages;

use App\Modules\Audience\Filament\Resources\SegmentMessages\SegmentMessageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageSegmentMessages extends ManageRecords
{
    protected static string $resource = SegmentMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Créer un message'),
        ];
    }
}
