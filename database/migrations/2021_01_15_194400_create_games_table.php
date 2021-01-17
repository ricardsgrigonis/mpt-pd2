<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_one_id');
            $table->unsignedBigInteger('team_two_id');
            $table->unsignedBigInteger('main_referee_id');
            $table->unsignedBigInteger('line_referee_one_id');
            $table->unsignedBigInteger('line_referee_two_id');
            $table->date('game_date')->nullable(false);
            $table->integer('attendees')->default(0);
            $table->string('stadium')->nullable(false);
            $table->timestamps();

            $table->foreign('team_one_id')->references('id')->on('teams');
            $table->foreign('team_two_id')->references('id')->on('teams');
            $table->foreign('main_referee_id')->references('id')->on('referees');
            $table->foreign('line_referee_one_id')->references('id')->on('referees');
            $table->foreign('line_referee_two_id')->references('id')->on('referees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('games');
    }
}
