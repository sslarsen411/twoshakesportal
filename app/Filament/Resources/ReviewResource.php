<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Review;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Location;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
// use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Support\Enums\IconPosition;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\Summarizers\Range;
use App\Filament\Resources\ReviewResource\Pages;
use Filament\Tables\Columns\Summarizers\Average;
use App\Filament\Resources\ReviewResource\RelationManagers;


class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;
    protected static bool $shouldRegisterNavigation = true;
  public static function getNavigationBadge(): ?string {
    return static::getModel()::whereIn('location_id', Location::query()->whereIn('company_id', Company::query()->select('id')->where('users_id', Auth::id()))->pluck('id')->toArray())->count();
    }
//$query->whereIn('location_id', Location::query()->whereIn('company_id', Company::query()->select('id')->where('users_id', Auth::id()))->pluck('id')->toArray())
    protected static ?string $navigationIcon = 'heroicon-o-star';
    protected static ?string $navigationLabel = 'Two Shake Reviews';
    public static function canCreate(): bool{
        return false;
    }
    public static function form(Form $form): Form{       
        return $form
            ->schema([
                Forms\Components\TextInput::make('customer_id')
                    ->disabled(),
                Forms\Components\TextInput::make('location_id')
                    ->disabled(),
                Forms\Components\TextInput::make('rate')
                    ->disabled(),
                //    ->maxLength(10),
                Forms\Components\Textarea::make('answers')
                    ->disabled()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('review')
                    ->disabled()
                    ->columnSpanFull(),
                Select::make('status')
                    ->label('Review Progress')
                    ->options([
                        'Started' => 'Started',
                        'Completed' => 'Review generated',
                        'Verified' => 'Review posted',
                        'Negative' => 'No review - Customer Service',
                    ])    
            ]);
    }

    public static function table(Table $table): Table{
       
     //   dd($locations[0]->id);
        return $table
        ->modifyQueryUsing(fn (Builder $query) =>    
            $query->whereIn('location_id', Location::query()->whereIn('company_id', Company::query()->select('id')->where('users_id', Auth::id()))->pluck('id')->toArray())
        )     
            ->heading('Current Reviews')
            ->columns([
                TextColumn::make('customers.fullname')
                    ->label('Customer Name'),
                TextColumn::make('locations.addr')
                    ->label('Location Reviewed')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('rate')
                    ->alignCenter()
                    ->icon('heroicon-o-star')
                    ->iconPosition(IconPosition::After)
                     ->badge()
                     ->color(static function ($state): string {
                        if ($state >= 3 && $state < 5) {
                            return 'violet';
                        }                        
                        if ($state == 5) {
                            return 'success';
                        }                        
                        return 'danger';
                        })
                    ->sortable()
                    ->searchable(),
                TextColumn::make('review')
                    ->wrap(),
                TextColumn::make('status')
                    ->sortable()
                    ->searchable()
                    ->label('Review Progress')
                    ->badge()
                    ->colors([
                        'warning',
                        'success' => static fn ($state): bool => $state === 'Completed',
                        'violet' => static fn ($state): bool => $state === 'Verified',
                        'danger' => static fn ($state): bool => $state === 'Negative',
                    ]),
                    
               TextColumn::make('created_at')
                    ->dateTime('M d Y')
                    ->sortable(),
                   // ->toggleable(isToggledHiddenByDefault: true),
               TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('rate')
                    ->numeric()
                    ->summarize([
                        Average::make(),                     
                ])
            ])
            ->filters([
                SelectFilter::make('location_id')
                ->label('Filter by Location')
                ->options(
                    Location::whereIn('company_id', Company::query()->select('id')
                    ->where('users_id', Auth::id()))
                    ->pluck('addr', 'id')->toArray(),
                ),
                Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from'),
                        Forms\Components\DatePicker::make('created_until')->default(now()),
                    ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['created_from'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                        )
                        ->when(
                            $data['created_until'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                        );
                }),               
            ])
            ->actions([
               // Tables\Actions\EditAction::make(),
               // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                 //   Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageReviews::route('/'),
        ];
    }
}
