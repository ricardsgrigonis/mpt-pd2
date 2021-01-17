<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];


    /**
     * Get the players of the team
     */
    public function getPlayers()
    {
        return $this->hasMany(Player::class);
    }

    /**
     * Get games of the team one
     */
    public function getTeamOneGames()
    {
        return $this->hasMany(Game::class, 'team_one_id');
    }

    /**
     * Get games of the team two
     */
    public function getTeamTwoGames()
    {
        return $this->hasMany(Game::class, 'team_two_id');
    }

    /**
     * Get all games of the team
     */
    public function getAllTeamGames() {
        return $this->getTeamOneGames->merge($this->getTeamTwoGames);
    }
}
