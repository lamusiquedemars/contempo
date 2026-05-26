<?php

namespace App\Filament\Resources\NewsPosts;

use App\Filament\Resources\NewsPosts\Pages\ManageNewsPosts;
use App\Modules\News\Models\NewsPost;
use App\Support\Modules;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class NewsPostResource extends Resource
{
    protected static ?string $model = NewsPost::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Actualites';

    protected static ?string $modelLabel = 'actualite';

    protected static ?string $pluralModelLabel = 'actualites';

    protected static ?int $navigationSort = 20;

    public static function shouldRegisterNavigation(): bool
    {
        return Modules::enabled('news');
    }

    public static function canAccess(): bool
    {
        return Modules::enabled('news') && parent::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Titre')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Textarea::make('excerpt')
                    ->label('Resume')
                    ->columnSpanFull(),
                RichEditor::make('content')
                    ->label('Contenu')
                    ->columnSpanFull(),
                FileUpload::make('image_path')
                    ->label('Image')
                    ->directory('news')
                    ->image(),
                TextInput::make('seo_title')
                    ->label('Titre SEO'),
                Textarea::make('seo_description')
                    ->label('Description SEO')
                    ->columnSpanFull(),
                Toggle::make('is_published')
                    ->label('Publie')
                    ->required(),
                DateTimePicker::make('published_at')
                    ->label('Debut de publication'),
                DateTimePicker::make('expires_at')
                    ->label('Fin de publication')
                    ->default(fn () => now()->addDays((int) config('maracuja.news.default_duration_days', 30)))
                    ->helperText('Optionnel. Par defaut, une nouvelle actualite expire selon la duree configuree du starter.'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Titre')
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable(),
                ImageColumn::make('image_path'),
                TextColumn::make('seo_title')
                    ->searchable(),
                IconColumn::make('is_published')
                    ->label('Publie')
                    ->boolean(),
                TextColumn::make('published_at')
                    ->label('Debut')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('expires_at')
                    ->label('Fin')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
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
            'index' => ManageNewsPosts::route('/'),
        ];
    }
}
