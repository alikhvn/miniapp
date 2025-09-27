<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Models\User;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Auth;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::User;

    protected static ?string $label = 'Пользователь';
    protected static ?string $pluralLabel = 'Пользователи';

    protected static ?string $recordTitleAttribute = 'User';

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
                Forms\Components\TextInput::make('name')
                    ->label('Имя'),

                Forms\Components\TextInput::make('phone')
                    ->label('Телефон'),

                Forms\Components\TextInput::make('email')
                    ->label('Email'),

                Forms\Components\Select::make('role')
                    ->label('Роль')
                    ->options([
                        '1' => 'admin',
                        '2' => 'driver',
                        '3' => 'passenger',
                    ])
                    ->default(function () {
                        if (Auth::user()->hasRole('admin')) {
                            return 'admin';
                        } elseif (Auth::user()->hasRole('driver')) {
                            return 'driver';
                        }
                        return 'passenger';
                    })

            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->query(User::query())
            ->columns([
                TextColumn::make('id')
                    ->label('ID'),
                TextColumn::make('telegram_id')
                    ->label('Телеграм ID'),
                TextColumn::make('name')
                    ->label('Имя'),
                TextColumn::make('phone')
                    ->label('Телефон'),
                TextColumn::make('email')
                    ->label('Email'),
                TextColumn::make('role')
                    ->label('Роль'),
                TextColumn::make('car_info')
                    ->label('Машина'),
                TextColumn::make('license_plate')
                    ->label('Номер авто'),
                TextColumn::make('rating')
                    ->label('Рейтинг'),
                TextColumn::make('username')
                    ->label('Юзернейм'),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
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
