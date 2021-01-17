@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card-body">
                @if (session('errorMessage'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('errorMessage') }}
                    </div>
                @endif
                @if (session('warningMessage'))
                    <div class="alert alert-warning" role="alert">
                        {{ session('warningMessage') }}
                    </div>
                @endif
                @if (session('successMessage'))
                    <div class="alert alert-success" role="alert">
                        {{ session('successMessage') }}
                    </div>
                @endif

                <div class="container">
                    <div>Turnīra tabulu (komandas vieta tabulā pēc kārtas, nosaukums, iegūto punktu skaits, uzvaru un
                        zaudējumu skaits pamatlaikā, uzvaru un zaudējumu skaits papildlaikā, spēlēs gūto un zaudēto
                        vārtu skaits). Augstākā vietā jāatrodas komandai, kurai ir vairāk punktu. Vienādu punktu gadījumā
                        augstāk ir komanda ar vairāk uzvarām pamatlaikā.
                    </div>
                    <br>
                    @if($teamStandings == null)
                        <p>Nav datu.</p>
                    @else
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Komanda</th>
                                <th scope="col">GP</th>
                                <th scope="col">W</th>
                                <th scope="col">WOT</th>
                                <th scope="col">LOT</th>
                                <th scope="col">L</th>
                                <th scope="col">G</th>
                                <th scope="col">GA</th>
                                <th scope="col">+/-</th>
                                <th scope="col">P</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($teamStandings as $key => $teamData)
                                <tr>
                                    <th scope="row">{{ $key + 1 }}</th>
                                    <td>{{$teamData["name"]}}</td>
                                    <td>{{$teamData["games_played"]}}</td>
                                    <td>{{$teamData["wins"]}}</td>
                                    <td>{{$teamData["wins_ot"]}}</td>
                                    <td>{{$teamData["loses_ot"]}}</td>
                                    <td>{{$teamData["loses"]}}</td>
                                    <td>{{$teamData["goals"]}}</td>
                                    <td>{{$teamData["goals_against"]}}</td>
                                    <td>{{$teamData["goals"] - $teamData["goals_against"]}}</td>
                                    <td>{{$teamData["points"]}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
