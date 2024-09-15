<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use Filament\Actions;
use Actions\EditAction;
use App\Models\Customer;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\CustomerResource;

class ViewCustomer extends ViewRecord
{
    protected static string $resource = CustomerResource::class;
    protected static ?string $title = 'Customer Review Details';
    protected function getHeaderActions(): array{
        return [
            Actions\EditAction::make()
            ->slideOver()
            ->color('danger')
            ->form(Customer::getForm()),
            Action::class::make('back')
            ->url(CustomerResource::getUrl()) // or you can use url(static::getResource()::getUrl())
                ->button()
                ->color('gray'),
        ];
    }
}
