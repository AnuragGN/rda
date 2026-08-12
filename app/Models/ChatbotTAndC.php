<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotTAndC extends Model
{
    /* @var string */
    protected $table = 'chatbot_t_and_c';

    /* @var array */
    protected $fillable = ['title', 'content', 'is_active'];

    /* @var array */
    protected $casts = ['is_active' => 'boolean'];

    /**
     * Returns the single active T&C record (lowest id among is_active = true rows),
     * or null if none exist.
     *
     * @return static|null
     */
    public static function getActive(): ?self
    {
        return self::where('is_active', true)
            ->orderBy('id', 'asc')
            ->first();
    }
}
