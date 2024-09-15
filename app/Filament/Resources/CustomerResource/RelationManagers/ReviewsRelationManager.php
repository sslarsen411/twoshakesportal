<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Support\Enums\IconPosition;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class ReviewsRelationManager extends RelationManager
{
    protected static string $relationship = 'reviews';

    public function form(Form $form): Form    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('customer_id')
                    ->required()
                    ->maxLength(255),
            ]);
    }
    public function table(Table $table): Table    {
        return $table
            ->heading('Review')
            ->recordTitleAttribute('customer_id')
            ->paginated(false)
            ->columns([
                TextColumn::make('rate')
                    ->color('warning')
                    ->label('Overall Rating')
                    ->icon('heroicon-s-star')
                    ->iconPosition(IconPosition::Before),
                TextColumn::make('review')
                    ->wrap(),
                SelectColumn::make('status')
                    ->label('Review Progress')
                    ->options([
                        'Started' => 'Started',
                        'Completed' => 'Review generated',
                        'Verified' => 'Review posted',
                        'Negative' => 'Negative - Customer Service',
                ]), 
                TextColumn::make('created_at')
                    ->label('Review Date')
                    ->dateTime('M d, Y'),
            ])
            ->filters([
                //
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
}
