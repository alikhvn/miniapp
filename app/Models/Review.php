<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['driver_id', 'passenger_id', 'rating', 'comment', 'is_anonymous', 'passenger_name'];

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function passenger()
    {
        return $this->belongsTo(User::class, 'passenger_id');
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

}
