<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;


class LocationsRelationManager extends RelationManager
{
    protected static string $relationship = 'locations';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('addr')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
        ->heading('Review Location')
            ->recordTitleAttribute('addr')
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Location ID'),
                Tables\Columns\TextColumn::make('addr')
                    ->label('Street address'),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Phone number')
                    ->formatStateUsing(fn (string $state) => fromE164($state)),
                // Tables\Columns\TextColumn::make('gbp_url')
                // ->formatStateUsing(fn (string $state) => $this->getURL($state)),
            ])
            ->filters([
               // Customer::
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
    // public static function getUrl($inVal){
  
    //     return getShorty($inVal, 'butt');
    // }
}
