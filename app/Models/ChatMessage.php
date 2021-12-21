<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;
    protected $fillable = [
        'group_id',
        'user_id',
        'type',
        'content',
        'read_num',
        'is_push',
        'remarks'
    ];
}
