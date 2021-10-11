<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Booking;
use App\Models\User;

class Tracking extends Model
{
    protected $table = 'trackings';

    protected $fillable = [
        'booking_id',
        'tracking_time',
        'staff',
        'status'
    ];

    public function booking()
    {
        return $this->hasOne(Booking::class, 'id', 'booking_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'staff');
    }
}
