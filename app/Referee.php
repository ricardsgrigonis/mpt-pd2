<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Referee extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'last_name'
    ];

    /**
     * Get games as main referee
     */
    public function getGamesAsMainReferee()
    {
        return $this->hasMany(Game::class, 'main_referee_id');
    }

    /**
     * Get games as line referee one
     */
    public function getGamesAsRefereeOne()
    {
        return $this->hasMany(Game::class, 'line_referee_one_id');
    }

    /**
     * Get games as line referee two
     */
    public function getGamesAsRefereeTwo()
    {
        return $this->hasMany(Game::class, 'line_referee_two_id');
    }

    /**
     * Get all referee games
     */
    public function getAllRefereeGames() {
        return $this->getGamesAsMainReferee->merge($this->getGamesAsRefereeOne)->merge($this->getGamesAsRefereeTwo);
    }
}
