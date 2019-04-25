@extends('layouts.appMovistar')
@section('scripts')
    <script src="/js/app.js"></script>
    <link rel="stylesheet" href="vendor/bootstrap-select/dist/css/bootstrap-select.css">
    <script src="vendor/bootstrap-select/dist/js/bootstrap-select.js"></script>
@endsection
@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel-heading" align="center">
                    <h3>MOVISTAR ECUADOR</h3>
                </div>
                <div class="alert alert-success" role="alert">
                    <div align="center">
                        <h4>{{$mensaje}}</h4>
                        <br><a href="{{url('movistar')}}" class="nav nav-tabs"><span class="glyphicon glyphicon-upload"></span> Subir nueva base</a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <br>

@endsection