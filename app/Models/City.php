<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = [
        'name_en',
        'name_ru',
        'name_kz',
        'country',
        'iso2',
        'region',
        'capital',
        'lat',
        'lng',
        'population',
    ];
}

