<?php

namespace App\Modules\Audience\Filament\Resources\AudienceContacts\Pages;

use App\Modules\Audience\Actions\ImportContactsFromInquiries;
use App\Modules\Audience\Filament\Resources\AudienceContacts\AudienceContactResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;

class ManageAudienceContacts extends ManageRecords
{
    protected static string $resource = AudienceContactResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('importFromInquiries')
                ->label('Importer depuis Demandes')
                ->action(function (): void {
                    $result = ImportContactsFromInquiries::run();

                    Notification::make()
                        ->title('Import terminé')
                        ->body("Créés: {$result['created']} | Déjà présents: {$result['existing']}")
                        ->success()
                        ->send();
                }),
            CreateAction::make()->label('Créer un contact'),
        ];
    }
}
