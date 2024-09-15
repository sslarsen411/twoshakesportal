<?php

namespace App\Filament\Resources;

use Filament\Tables;
use App\Models\Company;
use App\Models\Location;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\LocationResource\Pages;


class LocationResource extends Resource{
    protected static ?string $model = Location::class;
    protected static ?string $navigationGroup = 'Admin';
    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    // public static function getNavigationBadge(): ?string {
    //     return static::getModel()::whereIn('company_id', Company::query()->select('id')->where('users_id', Auth::id()))->count();
    // }
    public static function getNavigationBadge(): ?string {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form{
        return $form
        ->schema(components: Location::getForm());
    }
    public static function table(Table $table): Table{ 
        return $table     
            ->paginated(false)
            ->columns([
                TextColumn::make('id')
                    ->label('ID'),
                TextColumn::make('companies.company')
                    ->label('Owned by'),            
                TextColumn::make('google_accountID')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('google_locationID')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('addr')
                    ->Label('Street Address')
                    ->searchable(),
                TextColumn::make('zip')
                    ->badge()
                    ->searchable(),
                TextColumn::make('phone')
                    ->formatStateUsing(fn (string $state) => fromE164($state))
                    ->searchable(),
                TextColumn::make('gbp_url')
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime('M d, Y')
                    ->sortable(),                    
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
               // ViewColumn::make('url')->view('filament.component.url')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Full Record'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                  //  Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    public static function getPages(): array{
        return [
            'index' => Pages\ManageLocations::route('/'),
        ];
    }
}
