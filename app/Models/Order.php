<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'passenger_id',
        'driver_id',
        'city_id',
        'status',
        'pickup_address',
        'destination_address',
        'price',
        'accepted_at',
        'started_at',
        'completed_at'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'accepted_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function passenger()
    {
        return $this->belongsTo(User::class, 'passenger_id');
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
