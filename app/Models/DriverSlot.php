<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverSlot extends Model
{
    protected $fillable = ['driver_id', 'city_id', 'start_time', 'end_time', 'is_available', 'date'];

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id', 'telegram_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
