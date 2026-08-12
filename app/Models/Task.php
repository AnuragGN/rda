<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tasks'; 
   
    protected $primaryKey = 'task_id'; // Specify the primary key column name if it's different from 'id'

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fund_id',
        'contact_id',
        'task_type',
        'subject',
        'description',
        'start_date',
        'end_date',
        'reminds_on',
        'status',
        'is_send_mail',
        'donor_email_address',
        'donor_contact_id',
        'task_priority',
        'created_by'
        // Add other columns here
    ];

    // Define relationships or additional model configurations here if needed
}
