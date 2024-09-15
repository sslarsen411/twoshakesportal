<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Company;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CompanyResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CompanyResource\RelationManagers;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;
    protected static ?string $navigationLabel = 'Business Clients';
    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $navigationGroup = 'Admin';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('users_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('company')
                    ->required()
                    ->maxLength(50),
                Forms\Components\TextInput::make('co_email')
                    ->email()
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('co_phone')
                    ->tel()
                    ->maxLength(45)
                    ->default(null),
                Forms\Components\TextInput::make('co_mobile')
                    ->maxLength(45)
                    ->default(null),
                Forms\Components\TextInput::make('min_rate')
                    ->required()
                    ->maxLength(4)
                    ->default(3),
                Forms\Components\Toggle::make('multi_loc')
                    ->required(),
                Forms\Components\TextInput::make('status')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('users.name')
                    ->label('Owner'),
                TextColumn::make('company')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('locations.addr')->label('Locations')
                    ->listWithLineBreaks()
                    ->bulleted(),
                TextColumn::make('co_email')
                    ->label('Company Email')
                    ->searchable(),
                TextColumn::make('co_phone')
                        ->label('Company Phone')
                        ->formatStateUsing(fn (string $state) => fromE164($state))
                        ->searchable(),
                TextColumn::make('co_mobile')
                        ->hidden(),
                TextColumn::make('min_rate')
                        ->label('Min \'Good\' Review')
                        ->alignCenter()
                        ->searchable(),
                IconColumn::make('multi_loc')
                        ->boolean(),
                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'success',
                        'warning' => static fn ($state): bool => $state === 'suspended',
                        'danger' => static fn ($state): bool => $state === 'cancelled',
                    ]) ,
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
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCompanies::route('/'),
        ];
    }
}
