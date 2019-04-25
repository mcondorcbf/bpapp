@extends('layouts.appsupervisor')
@section('content')


<div class="container col-xs-12 col-md-12 col-lg-12">
    <div class="row">
        <div class="col-xs-12 col-md-12 col-lg-12">
            <div class="panel panel-default">

            <div class="panel-heading"><a href="{{url('/home')}}"><strong><< Nueva Búsqueda</strong></a></div>



                <div class="panel-body">
                    <div class="form-group">
                        <div class="">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#deudor" data-toggle="tab">Cargar Datos</a></li>
                            <li class=""><a href="#conyugue" data-toggle="tab">Cargar Datos 2</a></li>
                        </ul>

                        <div class="tab-content tab-content-border">
                            <div class="tab-pane fade active in" id="deudor">
                                <div class="col-md-12 col-xs-12 col-lg-6" style="padding:0">
                                    <div class="container">
                                        <a href="{{ URL::to('downloadExcel/xls') }}"><button class="btn btn-success">Download Excel xls</button></a>
                                        <a href="{{ URL::to('downloadExcel/xlsx') }}"><button class="btn btn-success">Download Excel xlsx</button></a>
                                        <a href="{{ URL::to('downloadExcel/csv') }}"><button class="btn btn-success">Download CSV</button></a>

                                        <a href="{{ URL::to('leerConvertirExcel') }}"><button class="btn btn-warning">Leer convertir excel</button></a>
                                        <form style="border: 4px solid #a1a1a1;margin-top: 15px;padding: 10px;" action="{{ URL::to('importExcel') }}" class="form-horizontal" method="post" enctype="multipart/form-data">
                                            {{ csrf_field() }}
                                            <input type="file" name="import_file" />
                                            <button class="btn btn-primary">Import File</button>
                                        </form>

                                        <?php

                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="conyugue">
                                <div class="col-md-12 col-xs-12 col-lg-6" style="padding:0">

                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

@endsection