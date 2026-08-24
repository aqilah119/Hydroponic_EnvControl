<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Analytics extends Model
{
    protected $fillable = [
        'plant_name',
        'status',
        'anomaly_detected',
        'prediction'
    ];
}