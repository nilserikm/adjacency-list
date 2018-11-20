<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableStats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stats', function(Blueprint $table) {
            $table->increments('id');
            $table->float('clone_time')->nullable();
            $table->unsignedInteger('cloned_num')->nullable();
            $table->unsignedInteger('tree_num')->nullable();
            $table->unsignedInteger('table_num')->nullable();
            $table->float('read_time')->nullable();
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
        Schema::dropIfExists('stats');
    }
}
