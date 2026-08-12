<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketMedia extends Model
{
    protected $table = 'media';

    protected $primaryKey = 'id';

    protected $fillable = [
        'target_type',
        'target_id',
        'file_name',
        'file_path',
        'name',
        'created_at',
        'created_by',
    ];

    static public function getTicketAttachment($target_type,$target_id)
    {
        $media = TicketMedia::select(
            'media.id',
            'media.target_type', 
            'media.target_id', 
            'media.file_name', 
            'media.file_path',
            'media.name', 
            'media.created_at', 
            'media.created_by',
        )
        ->where([
            'media.target_type' => $target_type,'media.target_id' => $target_id
        ])
        ->orderBy('media.created_at', 'ASC')
        ->get();
        
        return $media;
    }
}
