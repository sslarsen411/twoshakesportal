<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Illuminate\Notifications\Notifiable;
use Filament\Forms\Components\DateTimePicker;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'company',
        'ctc_phone',
        'ctc_mobile',
        'min_rate',
        'multi_loc',
        'status',
        'password',
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function companies()    {
        return $this->hasMany(Company::class,'users_id');
    }
    public function customers()    {
        return $this->hasMany(Customer::class, 'users_id');
    }

    public static function getForm(): array{
        return [
            TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            TextInput::make('email')
                ->email()
                ->required()
                ->maxLength(255),
            TextInput::make('company')
                ->required()
                ->maxLength(45),
            TextInput::make('ctc_phone')
                ->tel()
                ->maxLength(45)
                ->default(null),
            TextInput::make('ctc_mobile')
                ->maxLength(45)
                ->default(null),
            TextInput::make('min_rate')
                ->required()
                ->maxLength(4)
                ->default(3),
            Toggle::make('multi_loc')
                ->required(),
            TextInput::make('status')
                ->required(),
            DateTimePicker::make('email_verified_at'),
            TextInput::make('password')
                ->password()
                ->required()
                ->maxLength(255),
            Toggle::make('isAdmin')
                    ->required(),

        ];
    }
}
