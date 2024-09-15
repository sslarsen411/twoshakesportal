<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    public $table = 'companies';
    protected $fillable = [        
        'company',
        'co_email',
        'co_phone',
        'co_mobile',
        'min_rate',
        'multi_loc',
        'status',
    ];
    public function users(){
        return $this->belongsTo(User::class, 'users_id');
    }
    public function locations(){
        return $this->hasMany(Location::class);
    }
    public function customers(){
        return $this->hasMany(Customer::class);
    }
}
