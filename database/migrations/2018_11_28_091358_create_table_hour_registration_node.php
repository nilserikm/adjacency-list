<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableHourRegistrationNode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hour_registration_node', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('hour_registration_id');
            $table->foreign('hour_registration_id')->references('id')->on('hour_registrations');
            $table->unsignedInteger('node_id');
            $table->foreign('node_id')->references('id')->on('nodes');
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
        Schema::dropIfExists('hour_registration_node');
    }
}
