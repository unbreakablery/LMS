<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->integer('equ_id')
                ->comment('equipment id');
            $table->integer('booking_user')
                ->comment('user id in booking');
            $table->date('booking_date')
                ->comment('booking date');
            $table->enum('status', ['0', '1', '2', '3', '4'])
                ->default('0')
                ->comment('0: booking, 1: booked/approved, 2: rejected, 3: cancelled, 4: returned');
            $table->date('booking_start')
                ->comment('booking period start date');
            $table->date('booking_end')
                ->comment('booking period end date');
            $table->integer('staff')
                ->comment('user id dealing with booking')
                ->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}
