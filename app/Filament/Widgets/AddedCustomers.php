<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use App\Models\Customer;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;

class AddedCustomers extends BaseWidget{
    protected static ?string $heading = 'New Customers';
    protected static ?int $sort = 3;
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Customer::where('users_id', Auth::id())->where('state', 'new')->orWhere('state', 'inSeq')
            )
            ->columns([
                TextColumn::make('first_name')
                    ->searchable(),
                TextColumn::make('last_name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('locations.addr')
                ->label('Location Visited'),       
                TextColumn::make('state')
                ->label('Status'),       
                TextColumn::make('how_added')
                ->label('Added by'),       
            ]);
    }
}
