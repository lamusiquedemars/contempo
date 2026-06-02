<?php

namespace App\Modules\Audience\Filament\Resources\AudienceSegments;

use App\Modules\Audience\Filament\Resources\AudienceSegments\Pages\ManageAudienceSegments;
use App\Modules\Audience\Filament\Resources\AudienceContacts\AudienceContactResource;
use App\Modules\Audience\Models\AudienceSegment;
use App\Support\Modules;
use BackedEnum;
use UnitEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Schema as SchemaFacade;

class AudienceSegmentResource extends Resource
{
    protected static ?string $model = AudienceSegment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleGroup;

    protected static ?string $navigationLabel = 'Segments de contacts';

    protected static UnitEnum|string|null $navigationGroup = 'Relation client';

    protected static ?string $modelLabel = 'segment';

    protected static ?string $pluralModelLabel = 'segments';

    protected static ?int $navigationSort = 30;

    public static function shouldRegisterNavigation(): bool
    {
        return Modules::enabled('audience') && self::hasAudienceTables();
    }

    public static function canAccess(): bool
    {
        return Modules::enabled('audience') && self::hasAudienceTables() && parent::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nom')
                    ->required(),
                Textarea::make('description')
                    ->label('Description')
                    ->helperText('Les contacts s’ajoutent depuis la liste Contacts, avec les filtres et les actions groupées.')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nom')
                    ->searchable(),
                TextColumn::make('description')
                    ->label('Description')
                    ->limit(80),
                TextColumn::make('contacts_count')
                    ->counts('contacts')
                    ->label('Contacts')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('eligible_contacts_count')
                    ->label('Éligibles')
                    ->state(fn (AudienceSegment $record): int => self::eligibleContactsCount($record))
                    ->numeric(),
                TextColumn::make('messages_count')
                    ->counts('messages')
                    ->label('Messages')
                    ->numeric()
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('viewContacts')
                    ->label('Voir les contacts')
                    ->icon(Heroicon::OutlinedUsers)
                    ->url(fn (AudienceSegment $record): string => AudienceContactResource::getUrl('index')
                        . '?' . http_build_query([
                            'tableFilters' => [
                                'segment' => [
                                    'value' => $record->getKey(),
                                ],
                            ],
                        ])),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageAudienceSegments::route('/'),
        ];
    }

    private static function hasAudienceTables(): bool
    {
        return SchemaFacade::hasTable('audience_segments') && SchemaFacade::hasTable('audience_contact_segment');
    }

    private static function eligibleContactsCount(AudienceSegment $segment): int
    {
        return $segment->contacts()
            ->where('accepts_email', true)
            ->whereNull('unsubscribed_at')
            ->count();
    }
}
