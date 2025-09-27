<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $guarded = false;
    protected function casts(): array
    {
        return [
            'name' => 'array',
        ];
    }
}

