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
    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">ACTUALIZAR</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="body">
                    <iframe class="embed-responsive-item" src="" id="frame" frameborder="0"></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Editar acciones - {{$tipo->descripcion}}</div>
                <div class="panel-body">
                    <div class="content">
                        <div class="links" id="centrales">
                            @foreach($acciones as $k)
                                <form class="form-horizontal panel panel-default" role="form" method="post" action="{{ url('/tipoAccionU') }}">
                                    {{ csrf_field() }}
                                    <div class="well col-md-12 col-lg-12">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>Acción:
                                                    <div class='input-group date col-md-6 col-lg-6'>
                                                        <input type="hidden" value="{{$k->id_accion}}" id="id_accion" name="id_accion" >
                                                        <input type="text" class="span2 form-control" value="{{$k->descripcion}}" id="descripcion" name="descripcion" required>
                                                        <span class="input-group-addon"></span>
                                                    </div>
                                                    <br>Peso:
                                                    <div class='input-group date col-md-2 col-lg-2'>
                                                        <input type="number" class="span2 form-control" value="{{$k->peso}}" id="peso" name="peso" min='0' max='100' required>
                                                        <span class="input-group-addon"></span>
                                                    </div>
                                                </th>
                                            </tr>
                                            </thead>
                                        </table>
                                        <button type="submit" class="btn btn-success">Actualizar</button>
                                        <a class="btn btn-danger" onclick="return confirmation({{$k->id_accion}});">Eliminar</a>
                                    </div>
                                </form>
                            @endforeach

                            <h4>Agregar nueva acción - {{$tipo->descripcion}}</h4>
                            <hr>
                            <form class="form-horizontal panel panel-default" role="form" method="post" action="{{ url('/tipoAccionN') }}">
                                {{ csrf_field() }}
                                <div class="well col-md-12 col-lg-12">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>Acción:
                                                <div class='input-group date col-md-6 col-lg-6'>
                                                    <input type="hidden" value="{{$tipo->id_tipo}}" id="id_tipo" name="id_tipo" >
                                                    <input type="text" class="span2 form-control" value="" id="descripcion" name="descripcion" required>
                                                    <span class="input-group-addon"></span>
                                                </div>
                                                <br>Peso:
                                                <div class='input-group date col-md-2 col-lg-2'>
                                                    <input type="number" class="span2 form-control" value="" id="peso" name="peso" min='0' max='100' required>
                                                    <span class="input-group-addon"></span>
                                                </div>
                                            </th>
                                        </tr>
                                        </thead>
                                    </table>
                                    <button type="submit" class="btn btn-primary">Agregar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection