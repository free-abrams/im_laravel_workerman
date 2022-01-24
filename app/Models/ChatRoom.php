<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    use HasFactory;
    
    protected $fillable = [
    	'inviter',
	    'invitee',
	    'type',
	    'room_name',
	    'room_introduction',
	    'status',
    ];
}
