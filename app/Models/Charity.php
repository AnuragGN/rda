<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Charity extends Model
{
    use HasFactory;
    
    protected $table = 'charity';

    // Primary key
    protected $primaryKey = 'id';

    // Fillable fields
    protected $fillable = [
        'name', 'description', 'mission', 'history', 'phone', 'email', 'address', 'website', 'updated_by'
    ];

    
}
