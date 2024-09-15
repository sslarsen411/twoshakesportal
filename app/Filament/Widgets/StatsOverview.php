<?php

namespace App\Filament\Widgets;

use App\Models\Review;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Location;
use Illuminate\Support\Facades\Auth;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget{
    protected function getStats(): array{       
        return [
            Stat::make('Total Reviews Generated', Review::whereIn('location_id', Location::query()->whereIn('company_id', Company::query()->select('id')
                ->where('users_id', Auth::id()))->pluck('id')->toArray())->count()),        
            Stat::make('Rating Average', round(Review::whereIn('location_id', Location::query()->whereIn('company_id', Company::query()->select('id')  
            ->where('users_id', Auth::id()))->pluck('id')->toArray())->pluck('rate')->avg(), 2))            
            ->descriptionIcon('heroicon-s-star')
            ->description('Stars')
            ->color('warning'),
            Stat::make('Customers in the Database', Customer::getModel()::where('users_id', Auth::id())->count()),
        ];
    }
}
