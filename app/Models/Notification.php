<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notification extends Model
{

    protected $fillable = [
        'id', 
        'type', 
        'notifiable_id', 
        'notifiable_type', 
        'data', 
        'read_at'
    ];

    // Disable auto-increment since we use UUID as primary key
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * Get the parent notifiable model (user, job campaign, etc.).
     */
    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

    /**
     * Get the "read_at" timestamp as a Carbon instance.
     */
    protected $casts = [
        'read_at' => 'datetime',
    ];
}
