<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plant extends Model
{
    protected $table = 'plants';

    protected $fillable = [

        'name',
        'category',

        'ph_min',
        'ph_max',

        'temp_min',
        'temp_max',

        'tds_min',
        'tds_max',

        'water_min',

        'status',
    ];

    // One Plant has many Staff
    public function staffs()
    {
        return $this->hasMany(Staff::class);
    }
}