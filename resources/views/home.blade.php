@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Protokola augšupielāde') }}</div>

                <div class="card-body">
                    @if (session('errorMessage'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('errorMessage') }}
                        </div>
                    @endif
                        @if (session('warningMessage'))
                            <div class="alert alert-warning" role="alert">
                                {!! session('warningMessage') !!}
                            </div>
                        @endif
                        @if (session('successMessage'))
                            <div class="alert alert-success" role="alert">
                                {!! session('successMessage') !!}
                            </div>
                        @endif
                        {{Session::forget('successMessage')}}
                        {{Session::forget('warningMessage')}}

                        <form action="{{ URL::to('/upload-files') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="file" name="upload[]" multiple>
                                </div>
                                <div class="col-md-6 text-md-right">
                                    <button type="submit" class="btn btn-success">Pievienot spēles protokolu</button>
                                </div>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
