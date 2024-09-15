<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Location;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Actions\ViewAction;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Support\Enums\IconPosition;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Filament\Resources\CustomerResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationGroup;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Filament\Resources\CustomerResource\Pages\ViewCustomer;
use App\Filament\Resources\CustomerResource\Pages\ManageCustomers;
use App\Filament\Resources\CustomerResource\RelationManagers\ReviewsRelationManager;
use App\Filament\Resources\CustomerResource\RelationManagers\LocationsRelationManager;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;
    protected ?string $heading = 'Custom Page Heading';
    protected static ?string $navigationLabel = 'Customer Data';
    protected static ?string $navigationIcon = 'heroicon-s-star';
    public static function getNavigationBadge(): ?string {
        return static::getModel()::where('users_id', Auth::id())->count();
    }
    public static function form(Form $form): Form{
        return $form
            ->schema(components: Customer::getForm());            
    }
    public static function table(Table $table): Table{        
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->where('users_id', Auth::id()) )
            ->heading('Customer and Review List')
            ->columns([
                TextColumn::make('index')
                    ->label('')
                    ->rowIndex(),                
                TextColumn::make('first_name')
                    ->searchable(),
                TextColumn::make('last_name')
                    ->sortable()
                    ->searchable(),
                    TextColumn::make('locations.addr')
                    ->label('Location Visited'),                    
                TextColumn::make('state')
                    ->searchable()
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'gray',
                        'danger' => static fn ($state): bool => $state === 'inSeq',
                        'success' => static fn ($state): bool => $state === 'Visited',
                    ]),
                TextColumn::make('reviews.rate')->label('Overall Rating')
                    ->sortable()                    
                    ->alignCenter()
                    ->icon('heroicon-o-star')
                    ->iconPosition(IconPosition::After)
                     ->badge()
                     ->color(static function ($state): string {
                        if ($state >= 3) {
                            return 'primary';
                        }                        
                        return 'danger';
                    }),       
                TextColumn::make('oauth_provider')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('oauth_uid')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),               
                TextColumn::make('email')
                //    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('phone')
                    ->formatStateUsing(fn (string $state) => fromE164($state))
                    ->toggleable(isToggledHiddenByDefault: true)
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
                SelectFilter::make('location_id')
                    ->label('Filter by Location')
                    ->options(
                        Location::whereIn('company_id', Company::query()->select('id')
                        ->where('users_id', Auth::id()))
                        ->pluck('addr', 'id')->toArray(),
                    ),
                SelectFilter::make('state')
                    ->label('Customer Status')
                    ->multiple()
                    ->options([
                        'New' => 'New Customer',
                        'inSeq' => 'In Email Sequence',
                        'Visited' => 'Started a Review'
                ]),
                SelectFilter::make('how_added')
                    ->multiple()
                    ->options([
                        'client' => 'Added by client',
                        'twoshakes' => 'Added during review',
                        'other' => 'Other'
                ]),                 
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Details')
                    ->tooltip('View or edit this customer'),
             //   Tables\Actions\EditAction::make()->slideOver()->color('info'),
                // Tables\Actions\DeleteAction::make()->color('danger')
               // ->tooltip('Remove from the database'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
public static function infolist(Infolist $infolist): Infolist{
    return $infolist     
        ->schema([
            Section::make('Personal Info')
            ->heading('Customer Info')
            ->columns(3)
            ->schema([
                TextEntry::make('first_name')
                ->size(TextEntry\TextEntrySize::Large)
                    ->label('Customer Name' )
                ->getStateUsing(function ($record ) {
                    return $record->first_name . ' ' . $record->last_name;
                }),
                TextEntry::make('email'),
                TextEntry::make('phone'),
                TextEntry::make('state')
                    ->badge()
                    ->colors([
                        'danger',
                        'warning' => static fn ($state): bool => $state === 'inSeq',
                        'success' => static fn ($state): bool => $state === 'Started',
                    ]),  
            ])
        ]);
}
    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCustomers::route('/'),
            'view' => Pages\ViewCustomer::route('/{record}'),
            'details' => Pages\CustomerReviews::route('/details'),
            
        ];
    }
    public static function getRelations(): array{
        return[
            RelationGroup::make('Reviews', [
               LocationsRelationManager::class,
               ReviewsRelationManager::class
            ]),
        ];
    }
    public function hasCombinedRelationManagerTabsWithForm(): bool{
        return true;
    }
}
