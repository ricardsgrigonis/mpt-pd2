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
                    <div>Turnīra desmit rezultatīvāko spēlētāju (sakārtoti pēc gūto vārtu skaita un
                        rezultatīvo piespēļu skaita dilstošā secībā) saraksts. Jānorāda vieta sarakstā pēc kārtas,
                        spēlētāja vārds un uzvārds, komandas nosaukums, gūto vārtu skaits un rezultatīvo piespēļu skaits.
                        Sarakstā augstāk jāatrodas spēlētājam, kas guvis vairāk vārtu, bet, vienāda gūto vārtu
                        skaita gadījumā, tam spēlētājam, kas vairāk reižu rezultatīvi piespēlējis.
                    </div>
                    <br>
                    @if($top10Scorers == null)
                        <p>Nav datu.</p>
                    @else
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Vārds</th>
                            <th scope="col">Uzvārds</th>
                            <th scope="col">Komanda</th>
                            <th scope="col">Loma</th>
                            <th scope="col">GP</th>
                            <th scope="col">G</th>
                            <th scope="col">A</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($top10Scorers as $key => $playerData)
                            <tr>
                                <th scope="row">{{ $key + 1 }}</th>
                                <td>{{$playerData["name"]}}</td>
                                <td>{{$playerData["last_name"]}}</td>
                                <td>{{$playerData["team_name"]}}</td>
                                <td>{{$playerData["position"]}}</td>
                                <td>{{$playerData["games_played"]}}</td>
                                <td>{{$playerData["goals"]}}</td>
                                <td>{{$playerData["assists"]}}</td>
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
