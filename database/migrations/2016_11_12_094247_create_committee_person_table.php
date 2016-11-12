<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommitteePersonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('committee_person', function (Blueprint $table) {
            $table->integer('committee_id');
            $table->integer('person_id');
            $table->string('role');
            $table->primary(['committee_id', 'person_id']);
        });

        Schema::drop('committees_persons');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('committee_person');

        Schema::create('committees_persons', function (Blueprint $table) {
            $table->integer('committee_id');
            $table->integer('person_id');
            $table->string('role');
            $table->primary(['committee_id', 'person_id']);
        });
    }
}
