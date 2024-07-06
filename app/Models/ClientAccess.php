<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientAccess extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'device_uuid',
        'device_os',
        'access_token',
        'fcm_token',
        'last_logged_in'
    ];
}
