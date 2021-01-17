<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Team;
use App\Player;
use App\Referee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Show the standings
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function standings()
    {
        $teamStandings = Team::select('*')
            ->orderBy('points', 'DESC')
            ->orderBy('wins', 'DESC')
            ->get()->toArray();
        return view('standings')->with(["teamStandings" => $teamStandings]);
    }

    /**
     * Show the top 10 scorers
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function top10scorers()
    {
        $top10Scorers = Player::select('players.*', 'teams.name as team_name')
            ->join('teams', 'players.team_id', '=', 'teams.id')
            ->orderBy('players.goals', 'DESC')
            ->orderBy('assists', 'DESC')
            ->limit(10)
            ->get()->toArray();
        return view('top10scorers')->with(["top10Scorers" => $top10Scorers]);
    }

    /**
     * Show the top referees
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function top10referees()
    {
        $top10Referees = DB::table('referees')
            ->select(DB::raw('name, last_name, games_judged_as_main, penalty_counter, ROUND(penalty_counter / games_judged_as_main, 2) AS average_penalty_counter'))
            ->where('games_judged_as_main', '>', 0)
            ->limit(10)
            ->orderBy('average_penalty_counter', 'DESC')
            ->get()->toArray();
        $top10Referees = json_decode(json_encode($top10Referees), true);
        return view('top10referees')->with(["top10Referees" => $top10Referees]);
    }

    /**
     * Show the top attendees
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function top10popularteams()
    {
        $top10PopularTeams = DB::select("SELECT
            t.name, t.games_played, ROUND(AVG(g.attendees), 0) average_attendee_count
            FROM games g
            LEFT JOIN teams t ON t.id IN (g.team_one_id, g.team_two_id)
            GROUP BY t.name, t.games_played
            ORDER BY average_attendee_count DESC
            LIMIT 10");

        $top10PopularTeams = json_decode(json_encode($top10PopularTeams), true);
        return view('top10popularteams')->with(["top10PopularTeams" => $top10PopularTeams]);
    }

    /**
     * Show the top 10 roughest players
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function top10roughest()
    {
        $top10Roughest = DB::select("SELECT
            p.name, p.last_name, p.position, p.yellow_cards, p.red_cards, t.name as team_name, p.yellow_cards + p.red_cards as total_penalty
            FROM players p
            LEFT JOIN teams t ON t.id = p.team_id
            WHERE p.yellow_cards > 0
            ORDER BY total_penalty DESC, p.red_cards DESC
            LIMIT 10");
        $top10Roughest = json_decode(json_encode($top10Roughest), true);
        return view('top10roughest')->with(["top10Roughest" => $top10Roughest]);
    }
}
