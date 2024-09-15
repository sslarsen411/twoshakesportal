<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use Filament\Actions;
use Illuminate\Contracts\View\View;
use Filament\Resources\Pages\ManageRecords;
use App\Filament\Resources\CustomerResource;

class ManageCustomers extends ManageRecords
{
    protected static string $resource = CustomerResource::class;

    // public function getHeader(): ?View{
    //     return view('filament.component.custom-header');
    // }
    protected function getHeaderActions(): array{
        return [
            Actions\CreateAction::make()->slideOver()->icon('heroicon-o-user')->label('Add Customers'),
        ];
    }


}
