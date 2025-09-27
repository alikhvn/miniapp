<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages\ListOrders;
use App\Models\City;
use App\Models\Order;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Forms\Form;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Transliterator;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ChartBar;

    protected static ?string $label = 'Заказ';
    protected static ?string $pluralLabel = 'Заказы';

    protected static ?string $recordTitleAttribute = 'Order';

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Order::query())
            ->columns([
                TextColumn::make('id')
                    ->label('ID'),
                TextColumn::make('passenger.name')
                    ->label('Пассажир'),
                TextColumn::make('driver.name')
                    ->label('Водитель'),
                TextColumn::make('city.name')
                    ->label('Город'),
                TextColumn::make('pickup_address')
                    ->label('Откуда'),
                TextColumn::make('destination_address')
                    ->label('Куда'),
                TextColumn::make('status')
                    ->label('Статус')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'accepted' => 'success',
                        'in_progress' => 'info',
                        'cancelled' => 'danger',
                        'completed' => 'primary',
                    }),
                TextColumn::make('price')
                    ->label('Стоимость'),
                TextColumn::make('accepted_at')
                    ->label('Принят'),
                TextColumn::make('started_at')
                    ->label('Начато'),
                TextColumn::make('completed_at')
                    ->label('Завершено')
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->filters([
                SelectFilter::make('city.name')
                    ->label('Город')
                    ->options(
                        City::query()
                            ->selectRaw("id, name->>'ru' as name_ru")
                            ->pluck('name_ru', 'id')
                            ->toArray()
                    )
                    ->multiple()
                    ->searchable(),
                SelectFilter::make('pickup_address')
                    ->label('Откуда')
                    ->options(Order::query()->pluck('pickup_address','id')->unique())
                    ->searchable(),
                SelectFilter::make('destination_address')
                    ->label('Куда')
                    ->options(Order::query()->pluck('destination_address', 'id')->unique())
                    ->searchable(),
            ]);

    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrders::route('/'),
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
