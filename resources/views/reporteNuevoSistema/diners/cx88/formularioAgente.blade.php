<?php
use App\reportesNuevoSistema\cuentasX88\tbl_contactabilidad as contactabilidad;
use App\reportesNuevoSistema\cuentasX88\tbl_estado_civil as estado_civil;
use App\reportesNuevoSistema\cuentasX88\tbl_informacion_laboral as informacion_laboral;
use App\reportesNuevoSistema\cuentasX88\tbl_ruc as ruc;
use App\reportesNuevoSistema\cuentasX88\tbl_motivo as motivo;
use App\reportesNuevoSistema\cuentasX88\tbl_sugerencia as sugerencia;
use App\reportesNuevoSistema\cuentasX88\tbl_opciones as opciones;
use App\reportesNuevoSistema\cuentasX88\tbl_accion as accion;
?>@extends('layouts.app')
@section('scripts')
<script src="/js/app.js"></script>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.3/css/bootstrap-select.min.css">
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="/assets/loading/jquery.loading-indicator.css">
<script src="/assets/loading/jquery.loading-indicator.js"></script>
<style>
    input[type="text"]:read-only {
        background: #f0f0f0;
    }

    textarea[readonly="readonly"], textarea[readonly] {
        background: #f0f0f0;
    }

    .col-lg-12 {
        width: 100%;
        margin-bottom: 10px;
    }
</style>

<style>
    .form-group input[type="checkbox"] {
        display: none;
    }

    .form-group input[type="checkbox"] + .btn-group > label span {
        width: 20px;
    }

    .form-group input[type="checkbox"] + .btn-group > label span:first-child {
        display: none;
    }
    .form-group input[type="checkbox"] + .btn-group > label span:last-child {
        display: inline-block;
    }

    .form-group input[type="checkbox"]:checked + .btn-group > label span:first-child {
        display: inline-block;
    }
    .form-group input[type="checkbox"]:checked + .btn-group > label span:last-child {
        display: none;
    }
</style>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-maxlength/1.7.0/bootstrap-maxlength.min.js"></script>

<script type="text/javascript">
    $('input').maxlength({
        alwaysShow: true,
        threshold: 10,
        warningClass: "label label-success",
        limitReachedClass: "label label-danger",
        separator: ' out of ',
        preText: 'You write ',
        postText: ' chars.',
        validate: true
    });
</script>
@endsection
@section('content')
    <div class="col-lg-10 col-lg-offset-1">
        {!! Form::open(array('url'=>'/gestionCx88','method'=>'POST','id'=>'Form'))!!}
        <div class="panel-heading"><h3 align="center">FORMULARIO CX88</h3></div>

        <div class="panel panel-default">
            <br>
            <div class="panel-body">
                <div class="col-lg-6 col-md-6" >
                    <div class="col-lg-12 col-md-12" >
                        <label class="col-lg-3">Nivel: * </label>
                        <div class='col-lg-3'>
                            <input type="text" class="form-control" value="{{$cuenta->nivel}}" id="nivel" name="nivel" readonly>
                            <input type="hidden" value="{{$cuenta->id}}" id="id" name="id">
                            <input type="hidden" value="{{$cuenta->estado_guardado}}" id="guardado" name="guardado">
                        </div>
                        <label class="col-lg-2">Riesgo: * </label>
                        <div class='col-lg-1'>
                            <input type="hidden" value="{{$riesgo}}" id="riesgo" name="riesgo">
                            ${{$riesgo}}
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12" >
                        <label class="col-lg-3">Nombres: *</label>
                        <span class='col-lg-8'>
                            <input type="text" class="form-control" value="{{$cuenta->nombre}}" id="nombres" name="nombres" readonly>
                        </span>
                    </div>

                    <div class="col-lg-12 col-md-12" >
                        <label class="col-lg-3">Cédula: *</label>
                        <span class='col-lg-3'>
                        <input type="text" class="form-control" value="{{$cuenta->cedula}}" id="cedula" name="cedula" readonly>
                    </span>
                    </div>

                    <div class="col-lg-12 col-md-12" >
                        <hr>
                        <label class="col-lg-4">Contactabilidad: *</label>
                        <span class='col-lg-7'>
                        {!! Form::select('contactabilidad',[$cuenta->contactabilidad => $cuenta->contactabilidad ? $cuenta->contactabilidad : '--- Seleccione uno ---']+contactabilidad::where('estado',1)->pluck("nombre","nombre")->all(),null,['id'=>'contactabilidad','class'=>'form-control','required'=>'required']) !!}
                        </span>
                    </div>

                    <div class="col-lg-12 col-md-12">
                        <label class="col-lg-4">Resultado final: *</label>
                        <div class='col-lg-7'>
                            {!! Form::select('resultadoFinal',[$cuenta->resultado_final => $cuenta->resultado_final ? $cuenta->resultado_final : '--- Seleccione uno ---']+accion::where('estado',1)->pluck("nombre","nombre")->all(),null,['id'=>'resultadoFinal','class'=>'form-control','required'=>'required']) !!}
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12">
                        <label class="col-lg-4">Motivo decisión final: *</label>
                        <div class='col-lg-7'>
                            {!! Form::select('motivoNoPago',[$cuenta->motivo_id => $cuenta->motivo ? $cuenta->motivo : '--- Seleccione uno ---']+motivo::where('estado',1)->pluck("nombre","id")->all(),null,['id'=>'motivoNoPago','class'=>'form-control','required'=>'required']) !!}
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12">
                        <label class="col-lg-4">Submotivo decisión final: *</label>
                        <div class='col-lg-7'>
                            {!! Form::select('submotivoNoPago',[$cuenta->submotivo_id => $cuenta->submotivo ? $cuenta->submotivo : '--- Seleccione uno ---'],null,['id'=>'submotivoNoPago','class'=>'form-control','required'=>'required']) !!}
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12">
                        <label class="col-lg-4">Sugerencia: *</label>
                        <div class='col-lg-7'>
                            {!! Form::select('sugerencia',[$cuenta->sugerencia ? $cuenta->sugerencia : '' => $cuenta->sugerencia ? $cuenta->sugerencia : '--- Seleccione uno ---']+sugerencia::where('estado',1)->pluck("nombre","nombre")->all(),null,['id'=>'sugerencia','class'=>'form-control','required'=>'required','id'=>'sugerencia']) !!}
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12" >
                        <hr>
                        <label class="col-lg-3">Gestión Telefónica: *</label>
                        <div class='col-lg-9'>
                            <textarea class="form-control" id="gestionTelefonica" name="gestionTelefonica" required onpaste="return false">{{$cuenta->gestion_telefonica}}</textarea>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12" >
                        <label class="col-lg-3">Gestión de campo: *</label>
                        <div class='col-lg-9'>
                            <textarea class="form-control" id="gestionCampo" name="gestionCampo" required onpaste="return false">{{$cuenta->gestion_campo}}</textarea>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12" >
                        <label class="col-lg-3">Gestión anterior: *</label>
                        <div class='col-lg-9'>
                            <textarea class="form-control" id="gestionAnterior" name="gestionAnterior" required onpaste="return false">{{$cuenta->gestion_anterior}}</textarea>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12" >
                        <hr/>
                        <label class="col-lg-3">Investigación: * </label>
                        <div class='col-lg-4'>
                                {!! Form::select('tipoInvestigacion',[$cuenta->investigacion_tipo => $cuenta->investigacion_tipo ? $cuenta->investigacion_tipo : '--- Seleccione uno ---']+informacion_laboral::where('estado',1)->pluck("nombre","nombre")->all(),null,['id'=>'tipoInvestigacion','class'=>'form-control','required'=>'required']) !!}
                        </div>
                        <div class='col-lg-5'>
                            <textarea class="form-control" id="investigacion" name="investigacion" onpaste="return false" required>{{$cuenta->investigacion}}</textarea>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12" >
                        <label class="col-lg-3">Profesion: *</label>
                        <div class='col-lg-9'>
                            <input class="form-control" value="{{$cuenta->profesion}}" id="profesion" name="profesion" required>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12" >
                        <label class="col-lg-3">RUC: *</label>
                        <div class='col-lg-4'>
                            {!! Form::select('ruc',[$cuenta->ruc_tipo => $cuenta->ruc_tipo ? $cuenta->ruc_tipo : '--- Seleccione uno ---']+ruc::where('estado',1)->pluck("nombre","nombre")->all(),null,['id'=>'ruc','class'=>'form-control','required'=>'required','id'=>'ruc','onchange'=>'fRuc()']) !!}
                        </div>

                        <label class="col-lg-1">Nro:*</label>
                        <div class='col-lg-4'>
                            @if($cuenta->ruc_tipo=='Activo')
                                <input class="form-control" type="text" value="{{$cuenta->ruc}}" id="nro" name="nro" maxlength="13" required onkeypress="return numeros(event)">
                            @else
                                <input class="form-control" type="text" value="" id="nro" name="nro" maxlength="13" required onkeypress="return numeros(event)" required disabled>
                            @endif
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12" >
                        <label class="col-lg-3">Actividad RUC: *</label>
                        <div class='col-lg-9'>
                            @if($cuenta->ruc_tipo=='Activo')
                                <textarea class="form-control" id="actividadRuc" name="actividadRuc" required>{{$cuenta->actividad_ruc}}</textarea>
                            @else
                                <textarea class="form-control" id="actividadRuc" name="actividadRuc" required disabled>{{$cuenta->actividad_ruc}}</textarea>
                            @endif

                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6" >
                    <div class="col-lg-12 col-md-12" >

                        <label class="col-lg-2">Llamada: *</label>
                        @if(count($llamadas)>0)
                        <div class='col-lg-2'><strong>Si</strong>
                            <input type="hidden" value="Si" id="llamada" name="llamada" readonly required>
                        </div>
                        <div class='col-lg-12'>
                            <div class="row panel panel-default">
                                <div class="panel-heading">Gestiones TLC <strong>({{count($llamadas)}})</strong></div>
                                <div class="panel-body" style="overflow-y: scroll; height: 200px">
                                    @foreach($llamadas as $llamada)
                                    <div class="row panel panel-default">
                                        <div class="panel-heading">{{$llamada->created_at}} (TLC)</div>
                                        <div class="panel-body">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <dl class="dl-horizontal">
                                                    <dt>Fecha de gestión</dt>
                                                    <dd>{{$llamada->created_at}} </dd>

                                                    <dt>Teléfono</dt>
                                                    <dd>{{$llamada->phone}}</dd>

                                                    <dt>Agente</dt>
                                                    <dd>{{$llamada->agent}}</dd>

                                                    <dt>Acción</dt>
                                                    <dd>{{$llamada->action}}</dd>

                                                    <dt>Motivo/Submotivo</dt>
                                                    <dd>{{$llamada->reason}} ({{$llamada->sub_reason}})
                                                    </dd>

                                                    <dt>Descripción</dt>
                                                    <dd>{{$llamada->description}}</dd>
                                                </dl>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>
                        @else
                            <div class='col-lg-2'><strong>No</strong>
                            <input type="hidden" value="No" id="llamada" name="llamada" readonly required>
                        </div>
                        @endif
                        <label class="col-lg-2">Visita: *</label>
                        @if(count($visitas)>0)
                            <div class='col-lg-2'><strong>Si</strong>
                                <input type="hidden" value="Si" id="visita" name="visita" readonly required>
                            </div>
                            <div class='col-lg-12'>
                                <div class="row panel panel-default">
                                    <div class="panel-heading">Gestiones CEX <strong>({{count($visitas)}})</strong></div>
                                    <div class="panel-body" style="overflow-y: scroll; height: 200px">
                                    @foreach($visitas as $visita)
                                        <div class="row panel panel-default">
                                            <div class="panel-heading">{{$visita->created_at}} (CEX)</div>
                                            <div class="panel-body">
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <dl class="dl-horizontal">
                                                        <dt>Fecha de gestión</dt>
                                                        <dd>{{$visita->created_at}} </dd>

                                                        <dt>Teléfono</dt>
                                                        <dd>{{$visita->phone}}</dd>

                                                        <dt>Agente</dt>
                                                        <dd>{{$visita->agent}}</dd>

                                                        <dt>Acción</dt>
                                                        <dd>{{$visita->action}}</dd>

                                                        <dt>Motivo/Submotivo</dt>
                                                        <dd>{{$visita->reason}} ({{$visita->sub_reason}})</dd>

                                                        <dt>Descripción</dt>
                                                        <dd>{{$visita->description}}</dd>

                                                        <dt>Fotografias</dt>
                                                        @foreach($visita->imagenes as $imagen)
                                                        <dd><img src="http://ncobefecapp.cobefec.com/history/demarches/reviews/image?path={{$imagen}}" width="150"></dd>
                                                        @endforeach
                                                    </dl>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class='col-lg-2'><strong>No</strong>
                                <input type="hidden" value="No" id="visita" name="visita" readonly required>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="panel-footer"></div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
            </div>
            <div class="panel-body">
                <div class="col-lg-12 col-md-12" >
                    <label class="col-lg-2">Estado Civil: *</label>
                    <div class='col-lg-2'>
                        {!! Form::select('estado_civil',[$cuenta->estado_civil => $cuenta->estado_civil ? $cuenta->estado_civil : '--- Seleccione uno ---']+estado_civil::where('estado',1)->pluck("nombre","nombre")->all(),null,['id'=>'estado_civil','class'=>'form-control','required'=>'required','id'=>'estado_civil','onchange'=>'estad_civ()']) !!}
                    </div>
                </div>

                @foreach($parientes as $pariente)
                <?php
                    $nombre='nombre_'.strtolower($pariente);
                    $cedula='cedula_'.strtolower($pariente);
                    $tel='tel_'.strtolower($pariente);
                    $gestel='ges_tel_'.strtolower($pariente);
                    $cel='cel_'.strtolower($pariente);
                ?>
                @if($cuenta->$nombre!='' || $cuenta->$cedula!=null || $cuenta->$tel!=null || $cuenta->$gestel!=null || $cuenta->$cel!=null)
                    <?php $display='display: block'; $check='checked'; $validador=1; $disabled='';?>
                @else
                    <?php $display='display: none'; $check=''; $validador=0; $disabled='disabled';?>
                @endif
                <div class="[ form-group ]">
                    <input type="checkbox" name="{{$pariente}}_datos" id="{{$pariente}}_datos" autocomplete="off" {{$check}} />
                    <div class="[ btn-group ]" onclick="datos_parientes('{{$pariente}}')">
                        <label for="{{$pariente}}_datos" class="[ btn btn-default ]">
                            <span class="[ glyphicon glyphicon-ok ]"></span>
                            <span> </span>
                        </label>
                        <label for="{{$pariente}}_datos" class="[ btn btn-default active ]">
                            Datos del {{$pariente}}
                        </label>
                    </div>
                </div>

                <div style="{{$display}}" id="id_{{$pariente}}">
                    <div class="col-lg-12 col-md-12" >
                        <hr>
                        <label class="col-lg-2">Nombre {{$pariente}}: *</label>
                        <div class='col-lg-4'>
                            <input class="form-control" value="{{$cuenta->$nombre}}" id="nombre{{$pariente}}" name="nombre{{$pariente}}" required {{$disabled}}>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12" >
                        <label class="col-lg-2">Número de Cédula: *</label>
                        <div class='col-lg-2'>
                            <input type="text" class="form-control" value="{{$cuenta->$cedula}}" id="cedula{{$pariente}}" name="cedula{{$pariente}}" required {{$disabled}} >
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12" >
                        <label class="col-lg-2">Telf. celular {{$pariente}}: *</label>
                        <div class='col-lg-1'>
                            <input type="text" class="form-control" value="{{$cuenta->$cel ? $cuenta->$cel : ''}}" id="celular{{$pariente}}" name="celular{{$pariente}}" {{$disabled}} onkeypress="return numeros(event,this,'cel')" minlength="10" >
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12" >
                        <label class="col-lg-2">Telf. convencional {{$pariente}}: *</label>
                        <div class='col-lg-1'>
                            <input type="text" class="form-control" value="{{$cuenta->$tel ? $cuenta->$tel : ''}}" id="tlfConvencional{{$pariente}}" name="tlfConvencional{{$pariente}}" {{$disabled}} onkeypress="return numeros(event,this,'conv')" >
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12" >
                        <label class="col-lg-2">Gestión telefónica {{$pariente}}: *</label>
                        <div class='col-lg-4'>
                            <textarea class="form-control" id="gestionTlf{{$pariente}}" name="gestionTlf{{$pariente}}" required {{$disabled}}>{{$cuenta->$gestel}}</textarea>
                        </div>
                    </div>
                    </div>
                    <input type="hidden" name="validador{{$pariente}}" id="validador{{$pariente}}" value="{{$validador}}">
                @endforeach

            </div>
            <div class="panel-footer"></div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
            </div>
            <div class="panel-body">

                <div class="col-lg-6">
                <div class="col-lg-12 col-md-12">
                    <hr>
                    <label class="col-lg-3">Calle principal: {{$requeridoaf}}</label>
                    <div class='col-lg-9'>
                        <input class="form-control" value="{{$cuenta->calle_principal}}" id="callePrincipal" name="callePrincipal" {{$requerido}}>
                    </div>
                </div>

                <div class="col-lg-12 col-md-12">
                    <label class="col-lg-3">Numeración: {{$requeridoaf}}</label>
                    <div class='col-lg-9'>
                        <input class="form-control" value="{{$cuenta->numeracion}}" id="numeracion" name="numeracion" {{$requerido}}>
                    </div>
                </div>

                <div class="col-lg-12 col-md-12">
                    <label class="col-lg-3">Calle Secundaria: {{$requeridoaf}}</label>
                    <div class='col-lg-9'>
                        <input class="form-control" value="{{$cuenta->calle_secundaria}}" id="calleSecundaria" name="calleSecundaria" {{$requerido}}>
                    </div>
                </div>

                <div class="col-lg-12 col-md-12">
                    <label class="col-lg-3">Sector: {{$requeridoaf}}</label>
                    <div class='col-lg-9'>
                        <input class="form-control" value="{{$cuenta->sector}}" id="sector" name="sector" {{$requerido}}>
                    </div>
                </div>

                <div class="col-lg-12 col-md-12">
                    <label class="col-lg-3">Parroquia: {{$requeridoaf}}</label>
                    <div class='col-lg-9'>
                        <input class="form-control" value="{{$cuenta->parroquia}}" id="parroquia" name="parroquia" {{$requerido}}>
                    </div>
                </div>

                <div class="col-lg-12 col-md-12">
                    <label class="col-lg-3">Ciudad: {{$requeridoaf}}</label>
                    <div class='col-lg-9'>
                        <input class="form-control" value="{{$cuenta->ciudad2}}" id="ciudad" name="ciudad" {{$requerido}}>
                    </div>
                </div>

                <div class="col-lg-12 col-md-12">
                    <label class="col-lg-3">Canton: {{$requeridoaf}}</label>
                    <div class='col-lg-9'>
                        <input class="form-control" value="{{$cuenta->canton}}" id="canton" name="canton" {{$requerido}}>
                    </div>
                </div>

                <div class="col-lg-12 col-md-12">
                    <label class="col-lg-3">Provincia: {{$requeridoaf}}</label>
                    <div class='col-lg-9'>
                        <input class="form-control" value="{{$cuenta->provincia}}" id="provincia" name="provincia" {{$requerido}}>
                    </div>
                </div>

                <div class="col-lg-12 col-md-12">
                    <label class="col-lg-3">Referencia: {{$requeridoaf}}</label>
                    <div class='col-lg-9'>
                        <input class="form-control" value="{{$cuenta->referencia}}" id="referencia" name="referencia" {{$requerido}}>
                    </div>
                </div>

                </div>

                <div class="col-lg-6">
                    <div class="col-lg-12 col-md-12">
                        <hr>
                        <label class="col-lg-4">Databook: *</label>
                        <div class='col-lg-4'>
                            {!! Form::select('databook',[$cuenta->databook => $cuenta->databook ? $cuenta->databook : '--- Seleccione uno ---']+opciones::where('estado',1)->pluck("nombre","nombre")->all(),null,['id'=>'databook','class'=>'form-control','required'=>'required']) !!}
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12">
                        <label class="col-lg-4">Páginas investigación: *</label>
                        <div class='col-lg-4'>
                            {!! Form::select('paginasInvestigacion',[$cuenta->paginas_investigacion => $cuenta->paginas_investigacion ? $cuenta->paginas_investigacion : '--- Seleccione uno ---']+opciones::where('estado',1)->pluck("nombre","nombre")->all(),null,['id'=>'paginasInvestigacion','class'=>'form-control','required'=>'required']) !!}
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12">
                        <label class="col-lg-4">Circulo familiar: *</label>
                        <div class='col-lg-4'>
                            {!! Form::select('circuloFamiliar',[$cuenta->circulo_familiar => $cuenta->circulo_familiar ? $cuenta->circulo_familiar : '--- Seleccione uno ---']+opciones::where('estado',1)->pluck("nombre","nombre")->all(),null,['id'=>'circuloFamiliar','class'=>'form-control','required'=>'required']) !!}
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12">
                        <label class="col-lg-4">Migración: *</label>
                        <div class='col-lg-4'>
                            {!! Form::select('migracion',[$cuenta->migracion => $cuenta->migracion ? $cuenta->migracion : '--- Seleccione uno ---']+opciones::where('estado',1)->pluck("nombre","nombre")->all(),null,['id'=>'migracion','class'=>'form-control','required'=>'required']) !!}
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12">
                        <label class="col-lg-4">Visita Domicilio: *</label>
                        <div class='col-lg-4'>
                            {!! Form::select('visita_domicilio',[$cuenta->visita_domicilio => $cuenta->visita_domicilio ? $cuenta->visita_domicilio : '--- Seleccione uno ---']+opciones::where('estado',1)->pluck("nombre","nombre")->all(),null,['id'=>'visita_domicilio','class'=>'form-control','required'=>'required']) !!}
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12">
                        <label class="col-lg-4">Visita Oficina: *</label>
                        <div class='col-lg-4'>
                            {!! Form::select('visita_oficina',[$cuenta->visita_oficina => $cuenta->visita_oficina ? $cuenta->visita_oficina : '--- Seleccione uno ---']+opciones::where('estado',1)->pluck("nombre","nombre")->all(),null,['id'=>'visita_oficina','class'=>'form-control','required'=>'required']) !!}
                        </div>
                    </div>
                </div>

            </div>
            <div class="panel-footer"></div>
        </div>

        <div class="col-lg-12 col-md-6" style="margin-bottom: 10px">
            <div class="col-lg-12 col-md-12">
                <a href="{{url('cuentasX88')}}" id="atras"  class="btn btn-primary"><span class="glyphicon glyphicon-backward"></span> Atras</a>
                @if($cuenta->estado_gestionado==0 || $cuenta->estado_devuelto==1)
                @if($cuenta->estado_devuelto<1)
                <button type="submit" id="guardar" class="guardar btn btn-warning" onclick="guardar_formulario()"><span class="glyphicon glyphicon-floppy-disk"></span> Guardar </button>
                @endif
                <div id="validar" class="btn btn-info" onclick="enviado()"><span class="glyphicon glyphicon-refresh"></span> Validar Parientes</div>
                <button id="enviar" name="enviar" value="1" class="btn btn-success" disabled><span class="glyphicon glyphicon-send"></span> Enviar </button>
                <input type="hidden" id="enviarOk" name="enviarOk" value="">
                @endif
            </div>
        </div>

        {!!Form::close()!!}
    </div>

<script>
//VALID PREVIA
if ($("#ruc").val()!='Activo'){
    document.getElementById('nro').value = "";
    document.getElementById('nro').disabled = true;
    document.getElementById('actividadRuc').value = "";
    document.getElementById('actividadRuc').disabled = true;
}else{
    document.getElementById('nro').disabled = false;
    document.getElementById('actividadRuc').disabled = false;
}
var contacta='';
var resultfin='';
var sugerencia='';
var validaContacto=0;
if ($("#sugerencia").val()=='Cuenta pagada.'){
    if($("#resultadoFinal").val()=='Refinancia' || $("#resultadoFinal").val()=='Ofrecimiento al Corte' || $("#resultadoFinal").val()=='Notificado'){
        if ($("#contactabilidad").val()=='Ubicable'){
            validaContacto=1;
            var formulario = $('#Form');
            $("input", formulario).each(function(){
                $(this).prop('required',false);
            });
            $("textarea", formulario).each(function(){
                $(this).prop('required',false);
            });
            $("select", formulario).each(function(){
                $(this).prop('required',false);
            });
            $("#gestionTelefonica").prop('required',true);
            $("#motivoNoPago").prop('required',true);
            $("#submotivoNoPago").prop('required',true);
            $("#sugerencia").prop('required',true);
        }
    }else{
        validaContacto=0;
        $('#Form').find('input, textarea,select').attr('required','required');
    }
}
else{
    if($("#resultadoFinal").val()=='Refinancia' || $("#resultadoFinal").val()=='Ofrecimiento al Corte' || $("#resultadoFinal").val()=='Notificado'){
        if ($("#contactabilidad").val()=='Ubicable'){
            validaContacto=1;
        }
    }else{
        $('#Form').find('input, textarea,select').attr('required','required');
    }
    $('#Form').find('input, textarea,select').attr('required','required');
}


if (parseFloat($("#riesgo").val())<10000){
    $("#callePrincipal").prop('required',false);
    $("#numeracion").prop('required',false);
    $("#calleSecundaria").prop('required',false);
    $("#sector").prop('required',false);
    $("#parroquia").prop('required',false);
    $("#ciudad").prop('required',false);
    $("#canton").prop('required',false);
    $("#provincia").prop('required',false);
    $("#referencia").prop('required',false);
}

$("select[name='contactabilidad']").change(function(){
    contacta= $(this).val();
    if (sugerencia=='Cuenta pagada.'){
        if(resultfin=='Refinancia' || resultfin=='Ofrecimiento al Corte' || resultfin=='Notificado'){
            if (contacta=='Ubicable'){
                validaContacto=1;
                var formulario = $(this).parents('form:first');
                $("input", formulario).each(function(){
                    $(this).prop('required',false);
                });
                $("textarea", formulario).each(function(){
                    $(this).prop('required',false);
                });
                $("select", formulario).each(function(){
                    $(this).prop('required',false);
                });

                $("#gestionTelefonica").prop('required',true);
                $("#motivoNoPago").prop('required',true);
                $("#submotivoNoPago").prop('required',true);
                $("#sugerencia").prop('required',true);
            }
        }else{
            validaContacto=0;
            $('#Form').find('input, textarea,select').attr('required','required');
        }
    }
    else{
        if(resultfin=='Refinancia' || resultfin=='Ofrecimiento al Corte' || resultfin=='Notificado'){
            if (contacta=='Ubicable'){
                validaContacto=1;
            }
        }else{
            $('#Form').find('input, textarea,select').attr('required','required');
        }
        $('#Form').find('input, textarea,select').attr('required','required');
    }
});
$("select[name='resultadoFinal']").change(function(){
    resultfin= $(this).val();
    if (sugerencia=='Cuenta pagada.'){
        if(resultfin=='Refinancia' || resultfin=='Ofrecimiento al Corte' || resultfin=='Notificado'){
            if (contacta=='Ubicable'){
                validaContacto=1;
                var formulario = $(this).parents('form:first');
                $("input", formulario).each(function(){
                    $(this).prop('required',false);
                });
                $("textarea", formulario).each(function(){
                    $(this).prop('required',false);
                });
                $("select", formulario).each(function(){
                    $(this).prop('required',false);
                });

                $("#gestionTelefonica").prop('required',true);
                $("#motivoNoPago").prop('required',true);
                $("#submotivoNoPago").prop('required',true);
                $("#sugerencia").prop('required',true);
            }
        }else{
            validaContacto=0;
            $('#Form').find('input, textarea,select').attr('required','required');
            $("#callePrincipal").prop('required',true);
            $("#numeracion").prop('required',true);
            $("#calleSecundaria").prop('required',true);
            $("#sector").prop('required',true);
            $("#parroquia").prop('required',true);
            $("#ciudad").prop('required',true);
            $("#canton").prop('required',true);
            $("#provincia").prop('required',true);
            $("#referencia").prop('required',true);

        }
    }
    else{
        if(resultfin=='Refinancia' || resultfin=='Ofrecimiento al Corte' || resultfin=='Notificado'){
            if (contacta=='Ubicable'){
                validaContacto=1;
            }
        }else{
            $('#Form').find('input, textarea,select').attr('required','required');
        }
        $('#Form').find('input, textarea,select').attr('required','required');
    }
});

$("select[name='sugerencia']").change(function(){
    sugerencia= $(this).val();
    if (sugerencia=='Cuenta pagada.'){
        if(resultfin=='Refinancia' || resultfin=='Ofrecimiento al Corte' || resultfin=='Notificado'){
            if (contacta=='Ubicable'){
                validaContacto=1;
                var formulario = $(this).parents('form:first');
                $("input", formulario).each(function(){
                    $(this).prop('required',false);
                });
                $("textarea", formulario).each(function(){
                    $(this).prop('required',false);
                });
                $("select", formulario).each(function(){
                    $(this).prop('required',false);
                });

                $("#gestionTelefonica").prop('required',true);
                $("#motivoNoPago").prop('required',true);
                $("#submotivoNoPago").prop('required',true);
                $("#sugerencia").prop('required',true);
            }
        }else{
            validaContacto=0;
            $('#Form').find('input, textarea,select').attr('required','required');
        }
    }
    else{
        if(resultfin=='Refinancia' || resultfin=='Ofrecimiento al Corte' || resultfin=='Notificado'){
            if (contacta=='Ubicable'){
                validaContacto=1;
            }
        }else{
            $('#Form').find('input, textarea,select').attr('required','required');
        }
        $('#Form').find('input, textarea,select').attr('required','required');
    }
});


function guardar_formulario(){
    document.getElementById("enviarOk").value='';
}
@if($cuenta->estado_gestionado==1 && $cuenta->estado_devuelto==0)
$('#Form').find('input, textarea,select').attr('readonly','readonly');
@endif

@if($cuenta->estado_devuelto==2)
$('#Form').find('input, textarea,select').attr('readonly','readonly');
@endif

$("select[name='motivoNoPago']").change(function(){
    var reporte_nro = $('#reporte_nro').val();
    $('#loader-icon'+reporte_nro).hide();
    var id= $(this).val();
    var token = $("input[name='_token']").val();
    var homeLoader = $('body').loadingIndicator({
        useImage: false,
    }).data("loadingIndicator");
    homeLoader.show();
    $.ajax({
        url: "/gMotivoNoPago",
        method: 'POST',
        data: {id:id, _token:token},
        success: function(data) {
            $("select[name='submotivoNoPago']").html('');
            $("select[name='submotivoNoPago']").html(data.options);
            $("#cuentas"+reporte_nro).html('');
            homeLoader.hide();
        }
    });
});

function estad_civ(){
    /*MOMENTANEO INVALIDA VALIDACION CONYUGUE
    if ($(document.getElementById('estado_civil')).val() != 'Casado/a' ){
        document.getElementById("Conyugue_datos").checked = false;
        document.getElementById('validadorConyugue').value=0;

        document.getElementById('nombreConyugue').value = "";
        document.getElementById('nombreConyugue').disabled = true;

        document.getElementById('cedulaConyugue').disabled = true;
        document.getElementById('cedulaConyugue').value = "";

        document.getElementById('tlfConvencionalConyugue').disabled = true;
        document.getElementById('tlfConvencionalConyugue').value = "";

        document.getElementById('gestionTlfConyugue').disabled = true;
        document.getElementById('gestionTlfConyugue').value = "";

        document.getElementById('celularConyugue').disabled = true;
        document.getElementById('celularConyugue').value = "";
    }else{
        document.getElementById("id_Conyugue").style.display='block';
        document.getElementById("Conyugue_datos").checked=true;
        document.getElementById('validadorConyugue').value=1;

        document.getElementById('nombreConyugue').disabled = false;
        document.getElementById('cedulaConyugue').disabled = false;
        document.getElementById('tlfConvencionalConyugue').disabled = false;
        document.getElementById('gestionTlfConyugue').disabled = false;
        document.getElementById('celularConyugue').disabled = false;
    }
    FIN INVALIDA VALIDACION CONYUGUE*/
}

function fRuc(){
    if ($("#ruc").val()!='Activo'){

        document.getElementById('nro').value = "";
        document.getElementById('nro').disabled = true;
        document.getElementById('actividadRuc').value = "";
        document.getElementById('actividadRuc').disabled = true;

    }else{
        document.getElementById('nro').disabled = false;
        document.getElementById('actividadRuc').disabled = false;
    }
}

$(document).on('click', '.guardar', function(){
    var formulario = $(this).parents('form:first');
    $("input", formulario).each(function(){
        $(this).prop('required',false);
    });
    $("textarea", formulario).each(function(){
        $(this).prop('required',false);
    });
    $("select", formulario).each(function(){
        $(this).prop('required',false);
    });
    document.getElementById('guardado').value=1;
});

function datos_parientes(pariente){
    if($(document.getElementById('estado_civil')).val()=='Casado/a') {
        //MOMENTANEO if(pariente=="Conyugue")document.getElementById("Conyugue_datos").checked = false;
    }
    if(document.getElementById(pariente+"_datos").checked==false)
    {
        document.getElementById("id_"+pariente).style.display='block';
        document.getElementById('nombre'+pariente).disabled = false;
        document.getElementById('cedula'+pariente).disabled = false;
        document.getElementById('tlfConvencional'+pariente).disabled = false;
        document.getElementById('gestionTlf'+pariente).disabled = false;
        document.getElementById('celular'+pariente).disabled = false;

        document.getElementById('validador'+pariente).value=1;
    }
    if(document.getElementById(pariente+"_datos").checked==true)
    {
        document.getElementById("id_"+pariente).style.display='none';
        document.getElementById('nombre'+pariente).disabled = true;
        document.getElementById('cedula'+pariente).disabled = true;
        document.getElementById('tlfConvencional'+pariente).disabled = true;
        document.getElementById('gestionTlf'+pariente).disabled = true;
        document.getElementById('celular'+pariente).disabled = true;
        document.getElementById('validador'+pariente).value=0;
    }
}

function enviado(){
    //MOMENTANEO FIX QUITA VALIDACIONES DE PARIENTES
    if(document.getElementById('estado_civil').value=='Casado/a'){
        document.getElementById('Conyugue_datos').value='off';
    }
    //FIN FIX
    //alert(document.getElementById('Conyugue_datos').value);
    <?php $val='';?>
    @foreach($parientes as $pariente)
        <?php $valida='validador'.$pariente; $val=$val.'parseInt(document.getElementById("'.$valida.'").value)+';?>
    @endforeach

    var validaContact=<?php echo $val;?>0;
    if (validaContacto==1){validaContact=1;}else{validaContact=<?php echo $val;?>0;}
    //console.log(validaContact);
    if (document.getElementById('estado_civil').value=='Casado/a'){
        if (validaContact==1)validaContact=0;
    }
    //parche invalida pariente
    validaContact=1;
    //fin parche invalida pariente
    if (validaContact==0)
    {
        document.getElementById('enviar').disabled = true;
        alert('Debe seleccionar por lo menos un pariente');
        return false;
        exit();
    }else{
        //$('#').prop('required',false);
        document.getElementById('enviar').disabled = false;
        document.getElementById("enviarOk").value='ok';
    }
}

function numeros(e,a,tipo){

    tecla = (document.all) ? e.keyCode : e.which;

    //Tecla de retroceso para borrar, siempre la permite
    if (tecla==8){
        return true;
    }

    // Patron de entrada, en este caso solo acepta numeros
    patron =/[0-9]/;
    tecla_final = String.fromCharCode(tecla);

    if((a.value).length==1 && tipo=='cel'){
        if (tecla_final!='9') {
            a.value='';
            a.value = '09';
        }
    }
    if((a.value).length==1 && tipo=='conv'){
        if (tecla_final!='0' && tecla_final!='1' && tecla_final!='8' && tecla_final!='9'){
            a.value='0';
        }else{
            return false;
        }
    }
    return patron.test(tecla_final);
}

//MOMENTANEO FIX QUITA VALIDACIONES DE PARIENTES
$('#Conyugue_datos').prop('required',false);
$('#Padre_datos').prop('required',false);
$('#Madre_datos').prop('required',false);
$('#Hijo_datos').prop('required',false);
$("#celularConyugue").prop('required',false);
$("#tlfConvencionalConyugue").prop('required',false);
$("#celularPadre").prop('required',false);
$("#tlfConvencionalPadre").prop('required',false);
$("#celularMadre").prop('required',false);
$("#tlfConvencionalMadre").prop('required',false);
$("#celularHijo").prop('required',false);
$("#tlfConvencionalHijo").prop('required',false);
//FIN FIX
</script>
@endsection