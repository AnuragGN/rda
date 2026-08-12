<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    use HasFactory;

    protected $table = 'user_preference';

    protected $fillable = [
        'contact_id', 'preferences'
    ];

    public $timestamps = true;

    // Cast preferences attribute to json
    protected $casts = [
        'preferences' => 'json',
    ];
}
