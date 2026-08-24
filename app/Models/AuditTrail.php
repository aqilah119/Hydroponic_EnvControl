<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditTrail extends Model
{
    protected $table = 'audit_trails';

    public $timestamps = false;

    protected $fillable = [

    'user_id',
    
    'user_name',

    'role',

    'assigned_crop',

    'action',

    'details',

    'ip_address',

    'created_at',

];
}