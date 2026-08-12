<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthMeeting extends Model
{
    use HasFactory;

    protected $table = 'auth_meeting';

    protected $fillable = [
        'contact_id',
        'platform',
        'access_token',
        'access_token_expires_at',
        'refresh_token',
        'refresh_token_expires_at',
    ];

    protected $dates = [
        'access_token_expires_at',
        'refresh_token_expires_at',
        'created_at',
        'updated_at',
    ];

}
