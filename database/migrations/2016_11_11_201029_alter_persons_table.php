<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPersonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('persons', function (Blueprint $table) {
            $table->integer('mdb_id');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('gender', 1);
            $table->date('birthday');
            $table->integer('state_id');
            $table->integer('district_id');
            $table->string('religion');
            $table->string('peerage');
            $table->string('title');
            $table->timestamps();
            $table->dropColumn('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('persons', function (Blueprint $table) {
            $table->dropColumn('mdb_id');
            $table->dropColumn('firstname');
            $table->dropColumn('lastname');
            $table->dropColumn('gender');
            $table->dropColumn('birthday');
            $table->dropColumn('state_id');
            $table->dropColumn('district_id');
            $table->dropColumn('religion');
            $table->dropColumn('peerage');
            $table->dropColumn('title');
            $table->dropTimestamps();
            $table->string('name');
        });
    }
}
