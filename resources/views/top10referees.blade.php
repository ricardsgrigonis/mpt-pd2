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
                    <div>TOP 10 turnīra "stingrāko" tiesnešu saraksts, kas sakārtots vidēji vienā spēlē
                        piešķirto sodu skaita dilšanas secībā.
                    </div>
                    <br>
                    @if($top10Referees == null)
                        <p>Nav datu.</p>
                    @else
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Vārds</th>
                            <th scope="col">Uzvārds</th>
                            <th scope="col">Spēļu skaits</th>
                            <th scope="col">Sodu skaits</th>
                            <th scope="col">Vidējais sodu skaits</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($top10Referees as $key => $refereeData)
                            <tr>
                                <th scope="row">{{ $key + 1 }}</th>
                                <td>{{$refereeData["name"]}}</td>
                                <td>{{$refereeData["last_name"]}}</td>
                                <td>{{$refereeData["games_judged_as_main"]}}</td>
                                <td>{{$refereeData["penalty_counter"]}}</td>
                                <td>{{$refereeData["average_penalty_counter"]}}</td>
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
