<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CharityFundMapping extends Model
{
    use HasFactory;

    protected $table = 'charity_fund_mapping';

    protected $fillable = [
        'charity_id', 'fund_id', 'updated_by'
    ];

   

}
