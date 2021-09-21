<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'sender',
        'receiver',
        'msg',
        'status',
        'read_time'
    ];
}
