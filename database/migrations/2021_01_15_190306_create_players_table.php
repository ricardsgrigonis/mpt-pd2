<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);
            $table->string('last_name')->nullable(false);
            $table->unsignedBigInteger('team_id');
            $table->string('number')->nullable(false);
            $table->string('position')->nullable(false);
            $table->integer('games_played')->default(0);
            $table->integer('games_started')->default(0);
            $table->bigInteger('time_played')->default(0);
            $table->integer('goals')->default(0);
            $table->string('assists')->default(0);
            $table->string('yellow_cards')->default(0);
            $table->string('red_cards')->default(0);
            $table->timestamps();

            $table->foreign('team_id')->references('id')->on('teams');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('players');
    }
}
