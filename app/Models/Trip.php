<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id', 'passenger_id', 'city_id', 'from_address', 'to_address',
        'price', 'status', 'started_at', 'completed_at', 'duration_minutes', 'notes'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'price' => 'decimal:2',
    ];

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function passenger()
    {
        return $this->belongsTo(User::class, 'passenger_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }
}
