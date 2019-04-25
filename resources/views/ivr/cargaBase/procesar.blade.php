@extends('layouts.appIvr')
@section('scripts')
    <script src="/js/app.js"></script>
    <link rel="stylesheet" href="vendor/bootstrap-select/dist/css/bootstrap-select.css">
    <script src="{{asset('vendor/bootstrap-select/dist/js/bootstrap-select.js')}}"></script>
    <script>


        function redireccionarPagina() {
            window.location = "{{asset('/ivr')}}";
        }
        setTimeout("redireccionarPagina()", 10000);
    </script>
@endsection
@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel-heading"><h3>PROCESANDO IVR ...</h3></div>
                <div class="panel panel-default">
                    <br>
                    <form class="form-horizontal" id="Form" role="form" method="post">
                        {{ csrf_field() }}
                        <div class="panel-body">
                            <div class='alert alert-success col-lg-12 col-md-12' align="center">
                                PROCESANDO ARCHIVO ...<br> <strong>{{$request->archivo}}</strong>
                            </div>
                            <div class="col-lg-12 col-md-6" style="margin-bottom: 10px">
                                <label class="col-lg-12">Cliente</label>
                                <div class="col-lg-12">
                                    {{$cliente->nombres}}
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-6" style="margin-bottom: 10px">
                                <label class="col-lg-12">Campaña</label>
                                <div class="col-lg-12">
                                    {{$campania->nombre_campania}}
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-6" style="margin-bottom: 10px">
                                <label class="col-lg-12">Script</label>
                                <div class="col-lg-12">
                                    {{$script->script}}
                                    <input type="hidden" name="scriptsselect" value="{{$request->scriptsselect}}">
                                    <input type="hidden" name="clienteselect" value="{{$request->clienteselect}}">
                                    <input type="hidden" name="campaniaselect" value="{{$request->campaniaselect}}">
                                    <input type="hidden" name="tiposcriptsselect" value="{{$request->tiposcriptsselect}}">
                                    <input type="hidden" name="total" value="{{$request->total}}">
                                    @for($i=0;$i<$request->total;$i++)
                                    <input type="hidden" name="excelp{{$i}}" value="{{$request->input('excelp'.$i)}}">
                                    <input type="hidden" name="basep{{$i}}" value="{{$request->input('basep'.$i)}}">
                                    @endfor

                                </div>
                            </div>
                            <div class="col-lg-12 col-md-6" align="center" id="resultado">
                                <img src="{{asset('images/procesando.gif')}}" id="procesando"/>
                            </div>

                            <div class="col-lg-12 col-md-6" style="margin-bottom: 10px">
                                <br>
                                <div class="col-lg-12 col-md-12">
                                    <a href="{{url('ivr')}}" id="atras"  class="btn btn-primary "><span class="glyphicon glyphicon-forward"></span> Finalizar</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection