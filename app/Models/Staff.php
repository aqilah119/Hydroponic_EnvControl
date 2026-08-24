<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $table = 'staff';

    protected $fillable = [
        'staff_id',
        'name',
        'user_id',
        'gender',
        'plant_id',
        'status',
        'address',
        'phone_number',
        'profile_picture',
    ];

    // Staff belongs to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Staff belongs to Plant
    public function plant()
    {
        return $this->belongsTo(Plant::class);
    }
}