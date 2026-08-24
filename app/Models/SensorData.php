<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SensorData extends Model
{
    protected $table = 'sensor_data';

    public $timestamps = false;

    protected $fillable = [
        'sensor_type',
        'value',
        'timestamp'
    ];
}