<?php
use App\reportesNuevoSistema\cuentasX88\tbl_contactabilidad as contactabilidad;
use App\reportesNuevoSistema\cuentasX88\tbl_estado_civil as estado_civil;
use App\reportesNuevoSistema\cuentasX88\tbl_informacion_laboral as informacion_laboral;
use App\reportesNuevoSistema\cuentasX88\tbl_ruc as ruc;
use App\reportesNuevoSistema\cuentasX88\tbl_motivo as motivo;
use App\reportesNuevoSistema\cuentasX88\tbl_sugerencia as sugerencia;
use App\reportesNuevoSistema\cuentasX88\tbl_opciones as opciones;
use App\reportesNuevoSistema\cuentasX88\tbl_accion as accion;
use App\reportesNuevoSistema\cuentasX88\tbl_observaciones as observaciones;
?>@extends('layouts.appsupervisor')
@section('scripts')
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

        <div class="panel-heading"><h3 align="center">FORMULARIO CX88</h3></div>

        <div id="formulario">
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
                        {!! Form::select('contactabilidad',[$cuenta->contactabilidad => $cuenta->contactabilidad ? $cuenta->contactabilidad : '--- Seleccione uno ---']+contactabilidad::where('estado',1)->pluck("nombre","nombre")->all(),null,['id'=>'contactabilidad','class'=>'form-control','readonly'=>'readonly']) !!}
                        </span>
                    </div>

                    <div class="col-lg-12 col-md-12">
                        <label class="col-lg-4">Resultado final: *</label>
                        <div class='col-lg-7'>
                            {!! Form::select('resultadoFinal',[$cuenta->resultado_final => $cuenta->resultado_final ? $cuenta->resultado_final : '--- Seleccione uno ---']+accion::where('estado',1)->pluck("nombre","nombre")->all(),null,['id'=>'resultadoFinal','class'=>'form-control','readonly'=>'readonly']) !!}
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12">
                        <label class="col-lg-4">Motivo decisión final: *</label>
                        <div class='col-lg-7'>
                            {!! Form::select('motivoNoPago',[$cuenta->motivo_id => $cuenta->motivo ? $cuenta->motivo : '--- Seleccione uno ---']+motivo::where('estado',1)->pluck("nombre","id")->all(),null,['id'=>'motivoNoPago','class'=>'form-control','readonly'=>'readonly']) !!}
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12">
                        <label class="col-lg-4">Submotivo decisión final: *</label>
                        <div class='col-lg-7'>
                            {!! Form::select('submotivoNoPago',[$cuenta->submotivo_id => $cuenta->submotivo ? $cuenta->submotivo : '--- Seleccione uno ---'],null,['id'=>'submotivoNoPago','class'=>'form-control','readonly'=>'readonly']) !!}
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12">
                        <label class="col-lg-4">Sugerencia: *</label>
                        <div class='col-lg-7'>
                            {!! Form::select('sugerencia',[$cuenta->sugerencia ? $cuenta->sugerencia : '' => $cuenta->sugerencia ? $cuenta->sugerencia : '--- Seleccione uno ---']+sugerencia::where('estado',1)->pluck("nombre","nombre")->all(),null,['id'=>'sugerencia','class'=>'form-control','readonly'=>'readonly','id'=>'sugerencia']) !!}
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
                        <div class="col-lg-12">
                            <div class="row panel panel-default">
                                <div class="panel panel-heading">Gestion Realizada</div>
                                <div class="panel panel-body">
                                    <?php echo $cuenta->gestion_realizada;?>
                                </div>
                            </div>
                        </div>
                        <label class="col-lg-2">Llamada: *</label>
                        @if(count($llamadas)>0)
                        <div class='col-lg-2'>
                            <input type="text" class="form-control" value="Si" id="llamada" name="llamada" readonly required>
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
                            <div class='col-lg-2'>
                            <input type="text" class="form-control" value="No" id="llamada" name="llamada" readonly required>
                        </div>
                        @endif
                        <label class="col-lg-2">Visita: *</label>
                        @if(count($visitas)>0)
                            <div class='col-lg-2'>
                                <input type="text" class="form-control" value="Si" id="visita" name="visita" readonly required>
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
                                                        <dd>{{$visita->action}}
                                                        </dd>

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
                            <div class='col-lg-2'>
                                <input type="text" class="form-control" value="No" id="visita" name="visita" readonly required>
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
                        {!! Form::select('estado_civil',[$cuenta->estado_civil => $cuenta->estado_civil ? $cuenta->estado_civil : '--- Seleccione uno ---']+estado_civil::where('estado',1)->pluck("nombre","nombre")->all(),null,['class'=>'form-control','required'=>'required','id'=>'estado_civil','onchange'=>'estad_civ()']) !!}
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
                    <div class="[ btn-group ]" >
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
                                <input type="text" class="form-control" value="{{$cuenta->$cel}}" id="celular{{$pariente}}" name="celular{{$pariente}}" required {{$disabled}} onkeypress="return numeros(event)" maxlength="10">
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12" >
                            <label class="col-lg-2">Telf. convencional {{$pariente}}: *</label>
                            <div class='col-lg-1'>
                                <input type="text" class="form-control" value="{{$cuenta->$tel}}" id="tlfConvencional{{$pariente}}" name="tlfConvencional{{$pariente}}" required {{$disabled}} onkeypress="return numeros(event)" maxlength="9">
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
                    <label class="col-lg-3">Calle principal: *</label>
                    <div class='col-lg-9'>
                        <input class="form-control" value="{{$cuenta->calle_principal}}" id="callePrincipal" name="callePrincipal" required>
                    </div>
                </div>

                <div class="col-lg-12 col-md-12">
                    <label class="col-lg-3">Numeración: *</label>
                    <div class='col-lg-9'>
                        <input class="form-control" value="{{$cuenta->numeracion}}" id="numeracion" name="numeracion" required>
                    </div>
                </div>

                <div class="col-lg-12 col-md-12">
                    <label class="col-lg-3">Calle Secundaria: *</label>
                    <div class='col-lg-9'>
                        <input class="form-control" value="{{$cuenta->calle_secundaria}}" id="calleSecundaria" name="calleSecundaria" required>
                    </div>
                </div>

                <div class="col-lg-12 col-md-12">
                    <label class="col-lg-3">Sector: *</label>
                    <div class='col-lg-9'>
                        <input class="form-control" value="{{$cuenta->sector}}" id="sector" name="sector" required>
                    </div>
                </div>

                <div class="col-lg-12 col-md-12">
                    <label class="col-lg-3">Parroquia: *</label>
                    <div class='col-lg-9'>
                        <input class="form-control" value="{{$cuenta->parroquia}}" id="parroquia" name="parroquia" required>
                    </div>
                </div>

                <div class="col-lg-12 col-md-12">
                    <label class="col-lg-3">Ciudad: *</label>
                    <div class='col-lg-9'>
                        <input class="form-control" value="{{$cuenta->ciudad2}}" id="ciudad" name="ciudad" required>
                    </div>
                </div>

                <div class="col-lg-12 col-md-12">
                    <label class="col-lg-3">Canton: *</label>
                    <div class='col-lg-9'>
                        <input class="form-control" value="{{$cuenta->canton}}" id="canton" name="canton" required>
                    </div>
                </div>

                <div class="col-lg-12 col-md-12">
                    <label class="col-lg-3">Provincia: *</label>
                    <div class='col-lg-9'>
                        <input class="form-control" value="{{$cuenta->provincia}}" id="provincia" name="provincia" required>
                    </div>
                </div>

                <div class="col-lg-12 col-md-12">
                    <label class="col-lg-3">Referencia: *</label>
                    <div class='col-lg-9'>
                        <input class="form-control" value="{{$cuenta->referencia}}" id="referencia" name="referencia" required>
                    </div>
                </div>
                </div>

                <div class="col-lg-6">
                    <div class="col-lg-12 col-md-12">
                        <hr>
                        <label class="col-lg-4">Databook: *</label>
                        <div class='col-lg-4'>
                            {!! Form::select('databook',[$cuenta->databook => $cuenta->databook ? $cuenta->databook : '--- Seleccione uno ---']+opciones::where('estado',1)->pluck("nombre","nombre")->all(),null,['id'=>'databook','class'=>'form-control','readonly'=>'readonly']) !!}
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12">
                        <label class="col-lg-4">Páginas investigación: *</label>
                        <div class='col-lg-4'>
                            {!! Form::select('paginasInvestigacion',[$cuenta->paginas_investigacion => $cuenta->paginas_investigacion ? $cuenta->paginas_investigacion : '--- Seleccione uno ---']+opciones::where('estado',1)->pluck("nombre","nombre")->all(),null,['id'=>'paginasInvestigacion','class'=>'form-control','readonly'=>'readonly']) !!}
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12">
                        <label class="col-lg-4">Circulo familiar: *</label>
                        <div class='col-lg-4'>
                            {!! Form::select('circuloFamiliar',[$cuenta->circulo_familiar => $cuenta->circulo_familiar ? $cuenta->circulo_familiar : '--- Seleccione uno ---']+opciones::where('estado',1)->pluck("nombre","nombre")->all(),null,['id'=>'circuloFamiliar','class'=>'form-control','readonly'=>'readonly']) !!}
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12">
                        <label class="col-lg-4">Migración: *</label>
                        <div class='col-lg-4'>
                            {!! Form::select('migracion',[$cuenta->migracion => $cuenta->migracion ? $cuenta->migracion : '--- Seleccione uno ---']+opciones::where('estado',1)->pluck("nombre","nombre")->all(),null,['id'=>'migracion','class'=>'form-control','readonly'=>'readonly']) !!}
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12">
                        <label class="col-lg-4">Visita Domicilio: *</label>
                        <div class='col-lg-4'>
                            {!! Form::select('visita_domicilio',[$cuenta->visita_domicilio => $cuenta->visita_domicilio ? $cuenta->visita_domicilio : '--- Seleccione uno ---']+opciones::where('estado',1)->pluck("nombre","nombre")->all(),null,['id'=>'visita_domicilio','class'=>'form-control','readonly'=>'readonly']) !!}
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12">
                        <label class="col-lg-4">Visita Oficina: *</label>
                        <div class='col-lg-4'>
                            {!! Form::select('visita_oficina',[$cuenta->visita_oficina => $cuenta->visita_oficina ? $cuenta->visita_oficina : '--- Seleccione uno ---']+opciones::where('estado',1)->pluck("nombre","nombre")->all(),null,['id'=>'visita_oficina','class'=>'form-control','readonly'=>'readonly']) !!}
                        </div>
                    </div>
                </div>

            </div>
            <div class="panel-footer"></div>
        </div>

        </div>
        {!! Form::open(array('url'=>'/gestionCx88S','method'=>'POST','id'=>'Form'))!!}

        <div class="panel panel-default">
            <div class="panel-body">
                <label class="col-lg-1">Estado: </label>
                <div class="col-lg-3 col-md-6">
                    <select class="form-control" title="SELECCIONE UNO" name="estado_aprobado" id="estado_aprobado" required>
                        <option value="">Seleccione Uno</option>
                        <option value="1">APROBADO</option>
                        <option value="0">DEVUELTO</option>
                    </select>
                </div>
                <hr>
                    <label class="col-lg-2">Observaciones: </label>
                    <?php $observaciones=observaciones::where('id_cuenta',$cuenta->id)->get();?>
                    <div class="col-lg-12 col-md-12">
                    @foreach($observaciones as $observacion)
                        <?php $fecha=new DateTime($observacion->fecha);?>
                        - {{$fecha->format('Y-m-d')}} || {{$observacion->observacion}}<br>
                    @endforeach
                    </div>
                    <div class="col-lg-12 col-md-12">
                        <textarea class="form-control" name="observaciones" id="observaciones"></textarea>
                    </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-6" style="margin-bottom: 10px">
            <div class="col-lg-12 col-md-12">
                <a href="{{url('cuentasX88SG')}}" id="atras"  class="btn btn-primary"><span class="glyphicon glyphicon-backward"></span> Atras </a>
                <button type="submit" id="guardar" class="guardar btn btn-success"><span class="glyphicon glyphicon-floppy-disk"></span> Enviar </button>
                <input type="hidden" value="{{$cuenta->id}}" id="id" name="id">
            </div>
        </div>

        {!!Form::close()!!}
    </div>

<script>
    $('#formulario').find('input, textarea, button, select').attr('readonly','readonly');

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
    if ($(document.getElementById('estado_civil')).val() != 'CASADO/A' ){
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

        document.getElementById('gestionCelConyugue').disabled = true;
        document.getElementById('gestionCelConyugue').value = "";

    }else{
        document.getElementById("id_Conyugue").style.display='block';
        document.getElementById("Conyugue_datos").checked=true;
        document.getElementById('validadorConyugue').value=1;

        document.getElementById('nombreConyugue').disabled = false;
        document.getElementById('cedulaConyugue').disabled = false;
        document.getElementById('tlfConvencionalConyugue').disabled = false;
        document.getElementById('gestionTlfConyugue').disabled = false;
        document.getElementById('celularConyugue').disabled = false;
        document.getElementById('gestionCelConyugue').disabled = false;
    }
}

function fRuc(){
    if ($(document.getElementById('ruc')).val() != 'ACTIVO' ) {
        document.getElementById('nro').value = "";
        document.getElementById('nro').disabled = true;
        document.getElementById('actividadRuc').value = "";
        document.getElementById('actividadRuc').disabled = true;

    }else{
        document.getElementById('nro').disabled = false;
        document.getElementById('actividadRuc').disabled = false;
    }
}

function datos_parientes(pariente){
    if($(document.getElementById('estado_civil')).val()=='CASADO/A') {
        if(pariente=="Conyugue")document.getElementById("Conyugue_datos").checked = false;
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
    console.log('enviar');
    <?php $val='';?>
    @foreach($parientes as $pariente)
        <?php $valida='validador'.$pariente; $val=$val.'parseInt(document.getElementById("'.$valida.'").value)+';?>
    @endforeach

    var validaContacto=<?php echo $val;?>0;
    if (document.getElementById('estado_civil').value=='CASADO/A'){
        if (validaContacto==1)validaContacto=0;
    }

    console.log(validaContacto);
    if (validaContacto==0)
    {
        document.getElementById('enviar').disabled = true;
        alert('Debe seleccionar por lo menos un pariente');
        return false;
        exit();
    }else{
        document.getElementById('enviar').disabled = false;
        document.getElementById("enviarOk").value='ok';
    }
}

function numeros(e){
    tecla = (document.all) ? e.keyCode : e.which;

    //Tecla de retroceso para borrar, siempre la permite
    if (tecla==8){
        return true;
    }

    // Patron de entrada, en este caso solo acepta numeros
    patron =/[0-9]/;
    tecla_final = String.fromCharCode(tecla);
    return patron.test(tecla_final);
}
</script>
@endsection