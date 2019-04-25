@extends('layouts.appBmi')
@section('scripts')
    <script type="text/javascript" src="/js/datatables.min.js"></script>
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
                <div class="panel-heading">Nuevo producto </div>
                <div class="panel-body">
                    <div class="content">
                        <div class="links" id="centrales">
                            <form class="form-horizontal panel panel-default" role="form" method="post" action="{{url('/productosNu')}}">
                                {{ csrf_field() }}
                                <div class="well col-md-12 col-lg-12">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>Producto:
                                                <div class='input-group date col-md-6 col-lg-6'>
                                                    <input type="hidden" value="" id="id_producto" name="id_producto" >
                                                    <input type="text" class="span2 form-control" value="" id="descripcion" name="descripcion" required>
                                                    <span class="input-group-addon"></span>
                                                </div>
                                            </th>
                                        </tr>
                                        </thead>
                                    </table>
                                    <a href="{{url('productos')}}" class="btn btn-success" id="getSelected">< Regresar</a>
                                    <button type="submit" class="btn btn-primary">Guardar</button>
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