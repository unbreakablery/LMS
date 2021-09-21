<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrackingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trackings', function (Blueprint $table) {
            $table->id();
            $table->integer('booking_id')
                ->comment('booking id');
            $table->timestamp('tracking_time', $precision = 0)
                ->comment('tracking timestamp');
            $table->integer('staff')
                ->comment('user id dealing with booking');
            $table->enum('status', ['0', '1', '2', '3', '4', '5', '6'])
                ->default('0')
                ->comment('0: booking, 1: booked/approved, 2: rejected, 3: cancelled, 4: deleted, 5: returned, 6: changed booking info');
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
        Schema::dropIfExists('trackings');
    }
}
