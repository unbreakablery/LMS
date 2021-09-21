<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->string('equ_code')
                ->unique()
                ->comment('equipment registration code');
            $table->string('equ_name')
                ->comment('equipment name');
            $table->text('equ_desc')
                ->nullable()
                ->comment('equipment description/spec');
            $table->string('equ_image')
                ->nullable()
                ->comment('equipment image');
            $table->enum('equ_status', ['0', '1', '2'])
                ->default('0')
                ->comment('0: available, 1: booking, 2:pickup(booked)');
            $table->integer('cat_id')
                ->nullable()
                ->comment('category id');
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
        Schema::dropIfExists('equipment');
    }
}
