<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    public $table = 'reviews';

    protected $fillable = [
        'customer_id',
        'location_id',
        'rate',
        'answers',
        'review',
        'status'       
    ];    
    protected function casts(): array
    {
        return [
            'rate' => 'float',
        ];
    }

    public function locations()    {
        return $this->belongsTo(Location::class, 'location_id');
    }
    public function customers()    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }


}
