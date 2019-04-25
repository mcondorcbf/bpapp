@extends('layouts.appBmi')
@section('scripts')
    <script type="text/javascript" src="/js/datatables.min.js"></script>
    <script>
        function confirmation(id) {
            var answer = confirm("Seguro que desea eliminar esta acción?")
            if (answer){
                location.href='/tipoAccionD/'+id;
            }
            else{
                // do nothing
            }
        }
    </script>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">NUEVO PARAMETRO RANKING CLIENTES</div>
                <div class="panel-body">
                    <div class="content">
                        <div class="links" id="centrales">
                            {!!Form::open(array('url'=>'rankingAsesorNu','method'=>'POST'))!!}
                            {{ csrf_field() }}
                            <div class="panel-body">
                                <div class="col-lg-12 col-md-12" style="margin-bottom: 10px">
                                    <label class="col-lg-3">Valor Inicial *</label>
                                    <div class='col-lg-3 input-group'>
                                        <input type="hidden" value="" id="id_ranking" name="id_ranking">
                                        <input type="number" class="form-control" value="" id="valor_inicial" name="valor_inicial" required>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12" style="margin-bottom: 10px">
                                    <label class="col-lg-3">Valor Final: *</label>
                                    <div class='col-lg-3 input-group'>
                                        <input type="number" class="form-control" value="" id="valor_final" name="valor_final" required>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12" style="margin-bottom: 10px">
                                    <label class="col-lg-3">Nivel: *</label>
                                    <div class='col-lg-3 input-group'>
                                        <input type="number" class="form-control" value="" id="nivel" name="nivel" required>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12" >
                                    <label class="col-lg-3">Descripción: *</label>
                                    <div class='col-lg-6 input-group'>
                                        <input type="text" class="form-control" value="" id="descripcion" name="descripcion" required>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-6" style="margin-bottom: 10px">
                                    <br>
                                    <div class="col-lg-12 col-md-12">
                                        <a href="{{url('rankingClientes')}}" id="atras"  class="btn btn-primary"><span class="glyphicon glyphicon-backward"></span> Atras </a>
                                        <button type="submit" id="guardar"  class="btn btn-success"><span class="glyphicon glyphicon-floppy-disk"></span> Guardar </button>
                                    </div>
                                </div>
                            </div>
                            {!!Form::close()!!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection