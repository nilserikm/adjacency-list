<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableHourRegistrations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hour_registrations', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id');
            $table->float('efficiency');
            $table->dateTime('to');
            $table->dateTime('from');
            $table->unsignedInteger('break');
            $table->unsignedInteger('duration');
            $table->text('comment');
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
        Schema::dropIfExists('hour_registrations');
    }
}
