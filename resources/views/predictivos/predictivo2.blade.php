@extends('layouts.appPredictivos')
@section('scripts')

@endsection
@section('content')
    <div class="container col-xs-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">PREDICTIVO DINERS</div>

                    <div class="panel-body">
                        <div class="content">
                            <div class="links" id="centrales">
                                <nav class="navbar navbar-default">
                                    <div class="container-fluid">
                                        <div class="navbar-header">
                                            <a class="navbar-brand" href="#">
                                                {{$mensaje}} || Hora actual: {{date('H:i:s')}}
                                            </a>
                                        </div>
                                    </div>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection