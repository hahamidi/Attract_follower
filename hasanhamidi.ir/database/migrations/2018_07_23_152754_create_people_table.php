<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('people', function (Blueprint $table) {
            $table->string('id',30);
            $table->unsignedInteger('userid');
            $table->unsignedInteger('page');
            $table->string('username',100);
            $table->smallInteger('cond')->default(1);
            $table->boolean('absorb')->default(0);
            $table->unsignedInteger('target');
            $table->unsignedInteger('followed')->nullable();
            $table->unsignedInteger('unfollowed')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('people');
    }
}
