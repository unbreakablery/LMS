<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->integer('sender')
                ->default(1)
                ->comment('1: from superadmin(broadcast), other: from specific staff');
            $table->integer('receiver')
                ->default(0)
                ->comment('0: to all students(broadcast), other: to specific student');
            $table->text('msg')
                ->comment('notification content');
            $table->enum('status', ['0', '1', '2'])
                ->default('0')
                ->comment('0: sent, 1: read, 2: broadcast(in case user_id is 0)');
            $table->timestamp('read_time', $precision = 0)
                ->nullable()
                ->comment('read time stamp');
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
        Schema::dropIfExists('notifications');
    }
}
