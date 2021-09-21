<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'bookings';

    protected $fillable = [
        'equ_id',
        'booking_user',
        'booking_date',
        'status',
        'booking_start',
        'booking_end',
        'staff'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'booking_user');
    }

    public function staff_user()
    {
        return $this->hasOne(User::class, 'id', 'staff');
    }

    public function equipment()
    {
        return $this->hasOne(Equipment::class, 'id', 'equ_id');
    }

    public function getStatusName()
    {
        $this->attributes['status_name'] = getStatusName('booking', $this->status);
    }
}
