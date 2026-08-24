<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferenceRange extends Model
{
    protected $table = 'reference_ranges';

    public $timestamps = false;

    protected $fillable = [
        'parameter',
        'min_value',
        'max_value',
        'type',
        'plant_name'
    ];
}