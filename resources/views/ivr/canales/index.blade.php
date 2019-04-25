@extends('layouts.appIvr')
@section('scripts')

@endsection
@section('content')


<div class="container col-xs-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">CANALES IVRS</div>

                    <div class="panel-body">
                        <div class="content">
                            <div class="links" id="centrales">

                                <form class="form-horizontal panel panel-default" role="form" method="post" action="{{ url('/procesarCanalesIvr') }}">
                                    {{ csrf_field() }}
                                    <div class="well col-md-12 col-lg-12">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>Total canales:
                                                    <div class='input-group date col-md-2 col-lg-2' id='datetimepicker1'>
                                                        <input type="number" class="span2 form-control" value="{{$canales->canales}}" id="canales" name="canales" min='1' max='99999' required>
                                                        <span class="input-group-addon"></span>
                                                    </div>
                                                </th>
                                            </tr>
                                            </thead>
                                        </table>
                                        <button type="submit" class="btn btn-default">Actualizar</button>
                                    </div>
                                </form>
                                <h4>Editar número de canales de campañas activas</h4>
                                <?php echo $mensaje;?>
                                @foreach($campanias as $k)
                                <form class="form-horizontal panel panel-default" role="form" method="post" action="{{ url('/procesarCampanaIvr/'.$k->id_carga) }}">
                                    {{ csrf_field() }}
                                    <div class="well col-md-12 col-lg-12">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>Id: {{$k->id_carga}} <br> Cliente: {{$k->cliente}} <br> Campaña: {{$k->campania}} <br> Canales:
                                                    <div class='input-group date col-md-2 col-lg-2' id='datetimepicker1'>
                                                        <input type="hidden" value="{{$k->id_carga}}" id="id_carga" name="id_carga" >
                                                        <input type="number" class="span2 form-control" value="{{$k->canales}}" id="canales" name="canales" min='0' max='99999' required>
                                                        <span class="input-group-addon"></span>
                                                    </div>
                                                    <div>

                                                    </div>
                                                </th>
                                            </tr>
                                            </thead>
                                        </table>
                                        <button type="submit" class="btn btn-default">Actualizar</button>
                                    </div>
                                </form>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>

@endsection