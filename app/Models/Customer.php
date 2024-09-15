<?php

namespace App\Models;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Fieldset;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;
    public $table = 'customers';    
    protected $fillable = [
        'users_id',
        'location_id',
        'oauth_provider',
        'oauth_uid',
        'first_name',
        'last_name',
        'email',
        'phone', 
        'state',
        'how_added'  
    ];
    protected function casts(): array
    {
        return [
            'addr' => 'string',
         //   'status' => 'array',
        ];
    }    public function users()    {
        return $this->belongsTo(User::class);
    }
    public function locations()    {
        return $this->belongsTo(Location::class, 'location_id');
    }
    public function reviews()    {
        return $this->hasMany(Review::class, 'customer_id');
    } 

    public function locationAddr() {
        return Location::where('id', $this->location_id)->first();
    }
    public function getFullnameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }
    public static function getForm(): array{
        return [
            Hidden::make('users_id')
                ->default(Auth::id()),
            Fieldset::make('Review Details')             
                ->schema([
                // Select::make('location_id')
                //     ->label('Location to Review')
                //     ->required()
                //     ->markAsRequired(false)
                //     ->placeholder('Choose a Location')
                //     ->options(
                //         Location::where('users_id', Auth::id())
                //         ->pluck('addr', 'id')->toArray(),
                //     ),   
                Select::make('state')
                    ->label('Review Progress')
                    ->options([
                        'New' => 'New Customer',
                        'inSeq' => 'In Email Sequence',
                        'Visited' => 'Started a Review',                 
                    ])              
                    ->default('New'),
            ])
            ->columns(2),
            TextInput::make('oauth_provider')
                ->hidden()
                ->default('none'),
            TextInput::make('oauth_uid')
                ->hidden()
                ->maxLength(45)
                ->default(null),
            Fieldset::make('Contact Info')
                ->schema([
                    TextInput::make('first_name')
                        ->maxLength(45)
                        ->default(null),
                    TextInput::make('last_name')
                        ->maxLength(45)
                        ->default(null),
                    TextInput::make('email')
                        ->email()
                        ->maxLength(45)
                        ->default(null),
                    TextInput::make('phone')
                        ->tel()
                        ->maxLength(12)
                        ->default(null),
                ]),            
                Select::make('how_added')
                ->label('Source')
                ->options([
                    'client' => 'Added by client',
                    'twoshakes' => 'Added during review process',
                    'other' => '?',                 
                ])              
                ->default('twoshakes'),     
        ];
    }
        
}
