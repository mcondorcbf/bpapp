<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap.min.css" />
<style>
    body{
        font-size: 12px;
    }
    h4{
        font-size: 14px;
    }
</style>
<div class="panel-body">
    <div class="col-lg-4 col-lg-offset-4 panel">

        <div class="col-lg-12" align="center">
            <label class="col-lg-12" style="color: #9c1014">Para ingresar al sistema, debe ingresar una nueva contraseña</label>
        </div>


        <form class="form-horizontal" role="form" method="post" action="{{ url('/rcontrasena') }}">
            {{ csrf_field() }}
            <div class="col-lg-12">
                <label class="col-lg-6">Nombres: </label>
                <div class="col-lg-6">
                    <input type="hidden" value="{{$asesor->cedula_asesor}}" id="cedula_asesor" name="cedula_asesor">
                    <label>{{$asesor->nombres}}</label>
                </div>
            </div>

            <div class="col-lg-12">
                <label class="col-lg-6">Usuario: </label>
                <div class="col-lg-6">
                    <label>{{$asesor->email_corporativo}}</label>
                </div>
            </div>

            <div class="{{$estilo2}}">

                <div class="col-lg-12">
                    <label class="col-lg-6">Contraseña: </label>
                    <div class="col-lg-6">
                        <input class="form-control" type="password" name="contrasena_n" required>
                    </div>
                </div>

                <div class="col-lg-12">
                    <label class="col-lg-6">Repita la contraseña: </label>
                    <div class="col-lg-6">
                        <input class="form-control" type="password" name="contrasena_n2" required>
                    </div>
                </div>

                <div class="col-lg-12"> </div>

                <div class="col-lg-6 col-lg-offset-6">
                    <button type="submit" class="btn btn-success btn-md"><span class="glyphicon glyphicon-floppy-disk"></span> Guardar</button>
                </div>
            </div>

            <div class="col-lg-12 success {{$estilo}}">
                <ul>
                    <?php echo $mensaje;?>
                </ul>
            </div>
        </form>
    </div>

</div>

{{--<div id="destino">{{$clientesrk}}</div>--}}