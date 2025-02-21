<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Annoucement extends Model
{
    protected $fillable = [
        'title',
        'content',
        'image',
        'role',
        'start_date',
        'end_date'

    ];
}
