<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Fieldset;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Location extends Model
{
    use HasFactory;
    public $table = 'locations';
    protected $fillable = [
        'users_id',
        'client_id',
        'google_accountID',
        'google_locationID',
        'addr',
        'zip',
        'phone',
        'gbp_url',
    ];
    public function companies(){
        return $this->belongsTo(Company::class, 'company_id');
    }
    public function customers(){
        return $this->hasMany(Customer::class);
    }
    public function reviews(){
        return $this->hasMany(Review::class);
    }

    public static function getForm(): array{
        return[
            TextInput::make('users_id')
            ->default(Auth::id()),    
            Fieldset::make('Google Business Profile')                
                ->schema([    
                    TextInput::make('google_accountID') 
                        ->label('GBP ID')                   
                        ->maxLength(50)
                        ->default(null),
                    TextInput::make('google_locationID')
                        ->label('Location ID')                   
                        ->maxLength(50)
                        ->default(null),
                    TextInput::make('gbp_url')
                        ->label('Review Post URL')
                        ->maxLength(255)
                        ->default(null),
                ])
                ->columns(3),
            Fieldset::make('Location Details')                 
                ->schema([
                    TextInput::make('addr')
                        ->label('Street Address')
                        ->autofocus()
                        ->maxLength(50)
                        ->default(null),
                    TextInput::make('zip')
                        ->maxLength(5)
                        ->default(null),
                    TextInput::make('phone')
                        ->mask('9999999999')
                        ->tel()
                        ->maxLength(20)
                        ->default(null),
                ])
                ->columns(3),      
        ];
    }
}

