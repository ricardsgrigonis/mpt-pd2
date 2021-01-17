<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'last_name', 'number', 'team_id', 'position', 'games_played', 'games_started',
        'time_played', 'goals', 'assists', 'yellow_cards', 'red_cards'
    ];

    /**
     * Get the team that player belongs to.
     */
    public function getTeam()
    {
        return $this->belongsTo(Team::class);
    }
}
