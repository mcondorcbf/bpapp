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
    <div class="col-lg-12 col-md-12">
        <label class="col-lg-3">Nombres </label>
        <input type="hidden" value="{{$clientesrk->nombres}}" id="id_ranking_cliente" name="id_ranking_cliente"><label>{{$clientesrk->nombres}}</label>
    </div>

    <div class="col-lg-12 col-md-12">
        <label class="col-lg-3">Fecha de nacimiento: </label>
        {{$clientesrk->fecha_nacimiento}}
    </div>

    <div class="col-lg-12 col-md-12">
        <label class="col-lg-3">Edad: </label>
        {{$clientesrk->edad}}
    </div>

    <div class="col-lg-12 col-md-12">
        <label class="col-lg-3">Estado civil: </label>
        {{$clientesrk->estado_civil}}
    </div>

    <div class="col-lg-12 col-md-12">
        <label class="col-lg-3">Cel Movistar: </label>
        {{$clientesrk->celular_movistar}}
    </div>

    <div class="col-lg-12 col-md-12">
        <label class="col-lg-3">Email: </label>
        {{$clientesrk->email}}
    </div>

    <div class="col-lg-12 col-md-12">
        <label class="col-lg-3">Profesión: </label>
        {{$clientesrk->cod_profesion}}
    </div>

    <hr><h4><strong>DATOS LABORALES</strong></h4>
    <div class="col-lg-12 col-md-12">
        <label class="col-lg-3">Empresa: </label>
        {{$clientesrk->empresa->nombre}}
    </div>

    <div class="col-lg-12 col-md-12">
        <label class="col-lg-3">Dirección empresa: </label>
        {{$clientesrk->empresa->direccion}}
    </div>

    <div class="col-lg-12 col-md-12">
        <label class="col-lg-3">Fecha ingreso: </label>
        {{$clientesrk->empresa->fecha_ingreso}}
    </div>

    <div class="col-lg-12 col-md-12">
        <label class="col-lg-3">Cargo: </label>
        {{$clientesrk->cargo}}
    </div>

    <div class="col-lg-12 col-md-12">
        <label class="col-lg-3">Salario: </label>
        ${{$clientesrk->salario}}
    </div>

    <hr> <h4><strong>DATOS CONYUGUE</strong></h4>
    @foreach($parientes as $k)
        @if($k->id_parentesco==1 && $k->cedula!='')
            <div class="col-lg-12 col-md-12" >
                <label class="col-lg-3">Tipo: </label>
                {{$k->parentesco->descripcion}}
            </div>
            <div class="col-lg-12 col-md-12" >
                <label class="col-lg-3">Cédula: </label>
                {{$k->cedula}}
            </div>
            <div class="col-lg-12 col-md-12" >
                <label class="col-lg-3">Nombres: </label>
                {{$k->nombres}}
            </div>
            <div class="col-lg-12 col-md-12" >
                <label class="col-lg-3">Edad: </label>
                {{$k->edad}}
            </div>
            <div class="col-lg-12 col-md-12" >
                <label class="col-lg-3">Salario: </label>
                {{$k->salario}}
            </div>
            <div class="col-lg-12 col-md-12" >
                <label class="col-lg-3">Cargo: </label>
                {{$k->cargo}}
            </div>
            <div class="col-lg-12 col-md-12" >
                <label class="col-lg-3">Actividad: </label>
                {{$k->actividad}}
            </div>
        @else

        @endif
    @endforeach
        <hr> <h4><strong>DATOS ADICIONALES</strong></h4>
        <div class="col-lg-12 col-md-12" >
            {{$clientesrk->observacion}}
        </div>

</div>

{{--<div id="destino">{{$clientesrk}}</div>--}}