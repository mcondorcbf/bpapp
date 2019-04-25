@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">


                <div class="panel-heading"><a href="{{url('/home')}}"><strong><< Nueva Búsqueda</strong></a></div>

                <div class="panel-body">
                    <link rel="stylesheet" href="css/bootstrap.css">
                    <link rel="stylesheet" href="css/plugin/datatables/dataTables.bootstrap.css">
                    <link rel="stylesheet" href="css/plugin/datatables/dataTables.bootstrap.min.css">
                    <script src="js/libs/jquery/jquery-2.1.4.min.js"></script>
                    <script src="js/plugin/datatables/jquery.dataTables.min.js"></script>
                    <script src="js/plugin/datatables/dataTables.bootstrap.min.js"></script>
                    <script language="javascript">
                        $(document).ready(function() {
                            $(".botonExcel").click(function(event) {
                                $("#datos_a_enviar").val( $("<div>").append( $("#Exportar_a_Excel").eq(0).clone()).html());
                                $("#FormularioExportacion").submit();
                            });
                        });
                    </script>
                    @if(count($proceso_d)>0)
                    <div class="panel-heading" style="background-color: #3a77bf; border-color: #000; color: #fff;">CONSOLIDADO DINERS</div>
                        <table id="ttt" class="table table-hover table-condensed">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Valor</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($proceso_d as $key=>$value)
                            <tr>
                                <td bgcolor="#faf0e6"><?php echo $key; ?></td>
                                <td><?php echo $value; ?></td>
                            </tr>
                            @endforeach


                            </tbody>
                        </table>
                    @endif

                    @if(count($proceso_v)>0)
                        <div class="panel-heading" style="background-color: #777; border-color: #000; color: #fff;">CONSOLIDADO VISA</div>
                        <table id="ttt" class="table table-hover table-condensed">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Valor</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($proceso_v as $key=>$value)
                                <tr>
                                    <td bgcolor="#faf0e6"><?php echo $key; ?></td>
                                    <td><?php echo $value; ?></td>
                                </tr>
                            @endforeach


                            </tbody>
                        </table>
                    @endif

                    @if(count($proceso_dis)>0)
                        <div class="panel-heading" style="background-color: #ff9a22; border-color: #000; color: #fff;">CONSOLIDADO DISCOVER</div>
                        <table id="ttt" class="table table-hover table-condensed">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Valor</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($proceso_dis as $key=>$value)
                                <tr>
                                    <td bgcolor="#faf0e6"><?php echo $key; ?></td>
                                    <td><?php echo $value; ?></td>
                                </tr>
                            @endforeach


                            </tbody>
                        </table>
                    @endif

                    {{--<table id="ttt" class="table table-hover table-condensed">
                        <thead>
                        <tr>
                            @foreach($proceso_d as $key=>$value)
                                <td>{{$key}}</td>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            @foreach($proceso_d as $key=>$value)
                                <td>{{$value}}</td>
                            @endforeach
                        </tr>
                        </tbody>
                    </table>--}}

                    <form class="form-horizontal" role="form" method="post" action="{{ url('/generar') }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="proceso_d" value='{{serialize($proceso_d)}}'></input>
                        <input type="hidden" name="proceso_v" value='{{serialize($proceso_v)}}'></input>
                        <input type="hidden" name="proceso_dis" value='{{serialize($proceso_dis)}}'></input>
                        <button type="submit" class="btn btn-primary">
                            GUARDAR GESTION
                        </button>
                    </form>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection