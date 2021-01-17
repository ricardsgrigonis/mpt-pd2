<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'team_one_id', 'team_two_id', 'main_referee_id', 'line_referee_one_id', 'line_referee_two_id',
        'game_date', 'attendees', 'stadium'
    ];


    /**
     * Get the main referee of the game
     */
    public function getMainReferee()
    {
        return $this->hasOne(Referee::class, 'main_referee_id');
    }

    /**
     * Get the line referee one of the game
     */
    public function getLineRefereeOne()
    {
        return $this->hasOne(Referee::class, 'line_referee_one_id');
    }

    /**
     * Get the line referee two of the game
     */
    public function getLineRefereeTwo()
    {
        return $this->hasOne(Referee::class, 'line_referee_two_id');
    }

    /**
     * Get all referees of the team
     */
    public function getAllGameReferees() {
        return $this->getMainReferee->merge($this->getLineRefereeOne)->merge($this->getLineRefereeTwo);
    }
}
