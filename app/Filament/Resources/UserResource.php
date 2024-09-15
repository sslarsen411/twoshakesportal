<?php

namespace App\Filament\Resources;

 use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\UserResource\Pages;


class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    public static function getNavigationBadge(): ?string {
        return static::getModel()::count();
    }
    protected static ?string $navigationGroup = 'Admin';
    protected static ?string $navigationLabel = 'Users';
    

    public static function form(Form $form): Form{
        return $form
            ->schema(components: User::getForm());  
    }

    public static function table(Table $table): Table{
        return $table
            ->columns([
                TextColumn::make('name')
                ->width('5%')
                    ->searchable(),                
                    TextColumn::make('email')
                    ->icon('heroicon-m-envelope')
                    ->width('1%')
                    ->searchable(),
                TextColumn::make('companies.status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'success',
                        'warning' => static fn ($state): bool => $state === 'suspended',
                        'danger' => static fn ($state): bool => $state === 'cancelled',
                    ]) 
                    ->searchable(),               
                TextColumn::make('created_at')
                    ->dateTime('M d, Y')
                    ->sortable(),
                //    ->toggleable(isToggledHiddenByDefault: true),
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
            //    Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageUsers::route('/'),
        ];
    }
}