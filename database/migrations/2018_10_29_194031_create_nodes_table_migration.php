<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNodesTableMigration extends Migration
{
    public function up()
    {
        Schema::create('nodes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id')->index();
            $table->integer('parent_id')->unsigned()->nullable()->index();
            $table->integer('position', false, true);
            $table->integer('real_depth', false, true);
            $table->string('title');
            $table->unsignedInteger('estimate')->default(0);
            $table->softDeletes();

            $table->foreign('parent_id')
                ->references('id')
                ->on('nodes')
                ->onDelete('set null');
        });

        Schema::create('node_closure', function (Blueprint $table) {
            $table->increments('closure_id');

            $table->integer('ancestor', false, true);
            $table->integer('descendant', false, true);
            $table->integer('depth', false, true);

            $table->foreign('ancestor')
                ->references('id')
                ->on('nodes')
                ->onDelete('cascade');

            $table->foreign('descendant')
                ->references('id')
                ->on('nodes')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('node_closure', function (Blueprint $table) {
            Schema::dropIfExists('node_closure');
        });

        Schema::table('nodes', function (Blueprint $table) {
            Schema::dropIfExists('nodes');
        });
    }
}
