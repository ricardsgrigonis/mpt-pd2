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
                    <div>TOP 10 populārākās komandas, vērtējot pēc vidējā skatītāju skaita spēlē,
                        rezultāti sakārtoti dilstošā secībā.
                    </div>
                    <br>
                    @if($top10PopularTeams == null)
                        <p>Nav datu.</p>
                    @else
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Komanda</th>
                            <th scope="col">Spēļu skaits</th>
                            <th scope="col">Vidējais skatītāju skaits</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($top10PopularTeams as $key => $teamData)
                            <tr>
                                <th scope="row">{{ $key + 1 }}</th>
                                <td>{{$teamData["name"]}}</td>
                                <td>{{$teamData["games_played"]}}</td>
                                <td>{{$teamData["average_attendee_count"]}}</td>
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
