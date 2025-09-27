<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CitiesResource\Pages\CreateCities;
use App\Filament\Resources\CitiesResource\Pages\EditCities;
use App\Filament\Resources\CitiesResource\Pages\ListCities;
use App\Models\City;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Forms\Form;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Transliterator;

class CitiesResource extends Resource
{
    protected static ?string $model = City::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::BuildingOffice;

    protected static ?string $label = 'Город';
    protected static ?string $pluralLabel = 'Города';

    protected static ?string $recordTitleAttribute = 'City';

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('name.kz')
                    ->label('Казахский')
                    ->afterStateHydrated(
                        fn (?City $record, Set $set) => $record && $record->name['kk'] ? $set('name.kz', $record->name['kk']) : null
                    )
                    ->required(),

                Forms\Components\TextInput::make('name.ru')
                    ->label('Русский')
                    ->afterStateHydrated(
                        fn (?City $record, Set $set) => $record && $record->name['ru'] ? $set('name.ru', $record->name['ru']) : null
                    )
                    ->required(),

                Forms\Components\TextInput::make('name.en')
                    ->label('Английский')
                    ->afterStateHydrated(
                        fn (?City $record, Set $set) => $record && $record->name['en'] ? $set('name.en', $record->name['en']) : null
                    )
                    ->required(),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->query(City::query())
            ->columns([
                TextColumn::make('id')
                    ->label('ID'),
                TextColumn::make('name.en')
                    ->label('Английский'),
                TextColumn::make('name.ru')
                    ->label('Русский'),
                TextColumn::make('name.kz')
                    ->label('Казахский'),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCities::route('/'),
            'create' => CreateCities::route('/create'),
            'edit' => EditCities::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
