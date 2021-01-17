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
                    <div>Turnīra desmit rupjāko spēlētāju saraksts, sakārtojot tos dilstošā secībā pēc pārkāpumu skaita.
                        Vienāda skaita gadījumā augstāk tiek rādīti tie, kam ir vairāk sarkano kartīšu.
                    </div>
                    <br>
                    @if($top10Roughest == null)
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
                            <th scope="col">Dzeltenās</th>
                            <th scope="col">Sarkanās</th>
                            <th scope="col">Sodi kopā</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($top10Roughest as $key => $playerData)
                            <tr>
                                <th scope="row">{{ $key + 1 }}</th>
                                <td>{{$playerData["name"]}}</td>
                                <td>{{$playerData["last_name"]}}</td>
                                <td>{{$playerData["team_name"]}}</td>
                                <td>{{$playerData["position"]}}</td>
                                <td>{{$playerData["yellow_cards"]}}</td>
                                <td>{{$playerData["red_cards"]}}</td>
                                <td>{{$playerData["total_penalty"]}}</td>
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
