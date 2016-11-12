<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTwitterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('twitter', function (Blueprint $table) {
            $table->integer('person_id');
            $table->string('name');
            $table->boolean('verified');
            $table->integer('followers');
            $table->integer('friends');
            $table->integer('statuses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('twitter');
    }
}
