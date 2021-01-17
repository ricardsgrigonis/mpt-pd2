<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Team;
use App\Player;
use App\Game;
use App\Referee;
use Illuminate\Support\Facades\Session;

class FileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function postProtocolForm(Request $request)
    {
        $request->validate([
            'upload.*' => 'required|file|mimes:json|max:2048'
        ]);
        $successMesage = "";
        $warningMesage = "";
        if ($request->hasFile('upload')) {
            foreach ($request->upload as $file) {
                $content = file_get_contents($file);
                $input = iconv('UTF-8', 'UTF-8//IGNORE', utf8_encode($content));
                $json = json_decode($input, true);

                $teamOneTitle = $json["Spele"]["Komanda"][0]["Nosaukums"];
                $teamTwoTitle = $json["Spele"]["Komanda"][1]["Nosaukums"];
                $gameDate = $json["Spele"]["Laiks"];
                $attendees = $json["Spele"]["Skatitaji"];
                $stadium = $json["Spele"]["Vieta"];
                $gameHadOvertime = false;
                $teamOne = Team::firstOrCreate([
                    'name' => $teamOneTitle
                ]);
                $teamTwo = Team::firstOrCreate([
                    'name' => $teamTwoTitle
                ]);
                $duplicateGame = Game::where('game_date', $gameDate)->whereIn('team_one_id', array($teamOne->id, $teamTwo->id))->first();

                $mainReferee = Referee::firstOrCreate([
                    'name' => $json["Spele"]["VT"]["Vards"],
                    'last_name' => $json["Spele"]["VT"]["Uzvards"]
                ]);
                $mainReferee->games_judged_as_main += 1;
                $lineRefereeOne = Referee::firstOrCreate([
                    'name' => $json["Spele"]["T"][0]["Vards"],
                    'last_name' => $json["Spele"]["T"][0]["Uzvards"]
                ]);
                $lineRefereeTwo = Referee::firstOrCreate([
                    'name' => $json["Spele"]["T"][1]["Vards"],
                    'last_name' => $json["Spele"]["T"][1]["Uzvards"]
                ]);
                //izveidot speli un statistiku tikai spelei, kas nav dublikats
                if ($duplicateGame == null) {
                    Game::create([
                        'team_one_id' => $teamOne->id,
                        'team_two_id' => $teamTwo->id,
                        'main_referee_id' => $mainReferee->id,
                        'line_referee_one_id' => $lineRefereeOne->id,
                        'line_referee_two_id' => $lineRefereeTwo->id,
                        'game_date' => $gameDate,
                        'attendees' => $attendees,
                        'stadium' => $stadium
                    ]);

                    $teamOneGoals = 0;
                    $teamTwoGoals = 0;
                    //Saaku katras komandas datu apstradi
                    foreach ($json["Spele"]["Komanda"] as $teamData) {
                        $goalsScored = 0;
                        $team = Team::where('name', $teamData["Nosaukums"])->first();
                        $teamId = $team->id;
                        if (sizeof($team->getPlayers) == 0) {
                            //izveidot visus komandas speletajus, ja vel nebija
                            foreach ($teamData["Speletaji"]["Speletajs"] as $playerData) {
                                Player::create([
                                    'name' => $playerData["Vards"],
                                    'last_name' => $playerData["Uzvards"],
                                    'number' => $playerData["Nr"],
                                    'position' => $playerData["Loma"],
                                    'team_id' => $teamId
                                ]);
                            }

                        }
                        // pamatsastavs
                        foreach ($teamData["Pamatsastavs"]["Speletajs"] as $playerNumber) {
                            $player = Player::where('team_id', $teamId)->where('number', $playerNumber["Nr"])->first();
                            $player->games_played += 1;
                            $player->games_started += 1;
                            $player->save();
                        }
                        // mainas
                        if (isset($teamData["Mainas"]) && $teamData["Mainas"] != "") {
                            $changedPlayersNumbers = $teamData["Mainas"];
                            $isArrayOfArrays = array_filter($changedPlayersNumbers["Maina"], 'is_array') === $changedPlayersNumbers["Maina"];
                            if ($isArrayOfArrays) {
                                foreach ($changedPlayersNumbers["Maina"] as $playerChange) {
                                    $playerOn = Player::where('team_id', $teamId)->where('number', $playerChange["Nr2"])->first();
                                    $playerOn->games_played += 1;
                                    $playerOn->save();
                                }
                            } else {
                                $playerOn = Player::where('team_id', $teamId)->where('number', $changedPlayersNumbers["Maina"]["Nr2"])->first();
                                $playerOn->games_played += 1;
                                $playerOn->save();
                            }
                        }
                        // sodi
                        if (isset($teamData["Sodi"]) && $teamData["Sodi"] != "") {
                            $penaltyPlayersNumbers = $teamData["Sodi"];
                            $teamPenaltyArray = [];
                            $isArrayOfArrays = array_filter($penaltyPlayersNumbers["Sods"], 'is_array') === $penaltyPlayersNumbers["Sods"];
                            if ($isArrayOfArrays) {
                                foreach ($penaltyPlayersNumbers["Sods"] as $playerPenalty) {
                                    $player = Player::where('team_id', $teamId)->where('number', $playerPenalty["Nr"])->first();
                                    if (in_array($playerPenalty["Nr"], $teamPenaltyArray)) {
                                        $player->red_cards += 1;
                                    } else {
                                        $player->yellow_cards += 1;
                                    }
                                    array_push($teamPenaltyArray, $playerPenalty["Nr"]);
                                    $player->save();
                                    $mainReferee->penalty_counter += 1;
                                    $mainReferee->save();
                                }
                            } else {
                                $player = Player::where('team_id', $teamId)->where('number', $penaltyPlayersNumbers["Sods"]["Nr"])->first();
                                $player->yellow_cards += 1;
                                $player->save();
                                $mainReferee->penalty_counter += 1;
                                $mainReferee->save();
                            }
                        }

                        // varti
                        if (isset($teamData["Varti"]) && $teamData["Varti"] != "") {
                            $teamGoals = $teamData["Varti"];
                            $isArrayOfArrays = array_filter($teamGoals["VG"], 'is_array') === $teamGoals["VG"];
                            if ($isArrayOfArrays) {
                                foreach ($teamGoals["VG"] as $goal) {
                                    $player = Player::where('team_id', $teamId)->where('number', $goal["Nr"])->first();
                                    $player->goals += 1;
                                    $goalsScored += 1;
                                    $player->save();
                                    $goalTimeArray = explode(":", $goal["Laiks"]);
                                    $goalMinutes = intval($goalTimeArray[0]);
                                    $goalSeconds = intval($goalTimeArray[1]);
                                    $secondsFromGameStart = $goalMinutes * 60 + $goalSeconds;
                                    if ($secondsFromGameStart > 3600) {
                                        $gameHadOvertime = true;
                                    }
                                    // piespeles
                                    if (isset($goal["P"]) && $goal["P"] != "") {
                                        $assistPlayersNumbers = $goal["P"];
                                        $isArrOfArr = array_filter($assistPlayersNumbers, 'is_array') === $assistPlayersNumbers;
                                        if ($isArrOfArr) {
                                            foreach ($assistPlayersNumbers as $playerNumber) {
                                                $assistant = Player::where('team_id', $teamId)->where('number', $playerNumber)->first();
                                                $assistant->assists += 1;
                                                $assistant->save();
                                            }
                                        } else {
                                            $assistant = Player::where('team_id', $teamId)->where('number', $goal["P"]["Nr"])->first();
                                            $assistant->assists += 1;
                                            $assistant->save();
                                        }

                                    }
                                }
                            } else {
                                $player = Player::where('team_id', $teamId)->where('number', $teamGoals["VG"]["Nr"])->first();
                                $player->goals += 1;
                                $goalsScored += 1;
                                $player->save();
                                $goalTimeArray = explode(":", $teamGoals["VG"]["Laiks"]);
                                $goalMinutes = intval($goalTimeArray[0]);
                                $goalSeconds = intval($goalTimeArray[1]);
                                $secondsFromGameStart = $goalMinutes * 60 + $goalSeconds;
                                if ($secondsFromGameStart > 3600) {
                                    $gameHadOvertime = true;
                                }
                            }
                            if ($team->id == $teamOne->id) {
                                $teamOne->goals += $goalsScored;
                                $teamOneGoals = $goalsScored;
                                $teamTwo->goals_against += $goalsScored;
                            } else {
                                $teamTwo->goals += $goalsScored;
                                $teamTwoGoals = $goalsScored;
                                $teamOne->goals_against += $goalsScored;
                            }
                        }

                        $team->games_played += 1;
                        $team->save();

                    }
                    if ($teamOneGoals > $teamTwoGoals) {
                        if ($gameHadOvertime) {
                            //pirmie uzvar ot
                            $teamOne->wins_ot += 1;
                            $teamOne->points += 3;
                            $teamTwo->loses_ot += 1;
                            $teamTwo->points += 2;
                        } else {
                            //pirmie uzvar pamatlaika
                            $teamOne->wins += 1;
                            $teamOne->points += 5;
                            $teamTwo->loses += 1;
                            $teamTwo->points += 1;
                        }
                    } else {
                        if ($gameHadOvertime) {
                            //otrie uzvar ot
                            $teamTwo->wins_ot += 1;
                            $teamTwo->points += 3;
                            $teamOne->loses_ot += 1;
                            $teamOne->points += 2;
                        } else {
                            //otrie uzvar pamatlaika
                            $teamTwo->wins += 1;
                            $teamTwo->points += 5;
                            $teamOne->loses += 1;
                            $teamOne->points += 1;
                        }
                    }
                    $teamOne->save();
                    $teamTwo->save();
                    $successMesage .= "Fails " . $file->getClientOriginalName() . " apstrādāts veiksmīgi!<br>";
                } else {
                    $warningMesage .= "Fails " . $file->getClientOriginalName() . " netika apstrādāts, jo tajā norādītā spēle jau ir datubāzē!<br>";
                }
            }
            if($successMesage != "") {
                Session::put('successMessage', $successMesage);
            }
            if($warningMesage != "") {
                Session::put('warningMessage', $warningMesage);
            }
        } else {
            Session::put('warningMessage', 'Lūdzu augšupielādēt vismaz vienu failu!');
        }
        return redirect()->route('home');
    }
}
