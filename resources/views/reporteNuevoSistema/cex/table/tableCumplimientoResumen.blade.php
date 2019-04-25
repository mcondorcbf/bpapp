<table>
    <thead>
    <tr style="background-color:#66CCFF ">
        <th colspan="2">RESUMEN GENERAL DE CUMPLIMIENTO CEX</th>
    </tr>
    <tr>
        <th colspan="2">Fecha: {{$fecha_inicio}} - {{$fecha_fin}} / COBEFEC</th>
    </tr>
    </thead>
</table>

<table border="1">
    <thead>
    <tr>
        <th style="background-color: #FFFF00">MARCA</th>
        <th style="background-color: #B8CCE4">FECHA DE CARGA</th>
        <th style="background-color: #F2DCDB">VISITAS_ASIGNADAS</th>
        <th style="background-color: #B8CCE4">VISITAS_REALIZADAS</th>
        <th style="background-color: #FFFF00">VISITAS_NO_REALIZADAS</th>
        <th style="background-color: #DB9B99">CUMPLIMIENTO %</th>
        <th style="background-color: #C4BD97">CONTACTO DIRECTO</th>
        <th style="background-color: #C4BD97">CONTACTO INDIRECTO</th>
        <th style="background-color: #C4BD97">NO CONTACTADO</th>
        <th style="background-color: #C4BD97">EFECTIVIDAD %</th>
    </tr>
    </thead>
    <tbody>
    <?php $i=1;?>
    </tbody>
    <?php $tvasignadas=0;$tvrealizadas=0;$tvno_realizadas=0;$tcumplimiento=0;$tc_directo=0;$tc_indirecto=0;$tnocontactado=0;$tefectividad=0;$i=0;?>
    @foreach($reportes3 as $k)
        <tr>
            <td>{{$k['MARCA']}}</td>
            <td>{{$fecha_inicio}}</td>
            <td>{{$k['VISITAS_ASIGNADAS']}}</td><?php $tvasignadas=$tvasignadas+$k['VISITAS_ASIGNADAS']; ?>
            <td>{{$k['VISITAS_REALIZADAS']}}</td><?php $tvrealizadas=$tvrealizadas+$k['VISITAS_REALIZADAS']; ?>
            <td>{{$k['VISITAS_NO_REALIZADAS']}}</td><?php $tvno_realizadas=$tvno_realizadas+$k['VISITAS_NO_REALIZADAS']; ?>
            <td>{{$k['CUMPLIMIENTO']}}</td><?php $tcumplimiento=$tcumplimiento+$k['CUMPLIMIENTO']; ?>
            <td>{{$k['CONTACTO_DIRECTO']}}</td><?php $tc_directo=$tc_directo+$k['CONTACTO_DIRECTO']; ?>
            <td>{{$k['CONTACTO_INDIRECTO']}}</td><?php $tc_indirecto=$tc_indirecto+$k['CONTACTO_INDIRECTO']; ?>
            <td>{{$k['NO_CONTACTADO']}}</td><?php $tnocontactado=$tnocontactado+$k['NO_CONTACTADO']; ?>
            <td>{{$k['EFECTIVIDAD']}}</td><?php $tefectividad=$tefectividad+$k['EFECTIVIDAD']; $i++?>
            <?php $i++;?>
        </tr>
    @endforeach
    <tr style="background-color: #D9D9D9">
        <th></th>
        <th></th>
        <th>{{$tvasignadas}}</th>
        <th>{{$tvrealizadas}}</th>
        <th>{{$tvno_realizadas}}</th>
        <th>{{round($tcumplimiento/$i,2)}}</th>
        <th>{{$tc_directo}}</th>
        <th>{{$tc_indirecto}}</th>
        <th>{{$tnocontactado}}</th>
        <th>{{round($tefectividad/$i,2)}}</th>
    </tr>
</table>

<table border="1">
    <thead>
    <tr>
        <th colspan="4" style="background-color: #B496D5" align="center">EJECUTIVOS_CEX</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>CAPACIDAD INSTALADA</td>
        <td>SIN ASIGNACION</td>
        <td>CUMPLIMIENTO BAJO 70%</td>
        <td>CUMPLIMIENTO 100%</td>
    </tr>
    <tr>
        <th style="background-color: #D9D9D9">{{$reportes6[1]['Ejecutivos_cex']}}</th>
        <th style="background-color: #D9D9D9">{{$reportes6[2]['Ejecutivos_cex']}}</th>
        <th style="background-color: #D9D9D9">{{$reportes6[3]['Ejecutivos_cex']}}</th>
        <th style="background-color: #D9D9D9">{{$reportes6[4]['Ejecutivos_cex']}}</th>

    </tr>
    </tbody>
</table>

<table border="1">
    <thead>
    <tr>
        <th style="background-color: #B4B4D5">COORDINADOR</th>
        <th style="background-color: #DB9B99">CUMPLIMIENTO %</th>
        <th style="background-color: #C4BD97">EFECTIVIDAD %</th>
    </tr>
    </thead>
    <?php $tcumplimiento=0;$tefectividad=0;$i=0;?>
    @foreach($reportes4 as $k)
        <tr>
            <td>{{$k['COORDINADOR']}}</td>
            <td>{{round($k['CUMPLIMIENTO'],2)}}</td><?php $tcumplimiento=$tcumplimiento+$k['CUMPLIMIENTO']; ?>
            <td>{{round($k['EFECTIVIDAD'],2)}}</td><?php $tefectividad=$tefectividad+$k['EFECTIVIDAD']; $i++?>
        </tr>
    @endforeach
    <tr style="background-color: #D9D9D9">
        <th></th>
        <th>{{round($tcumplimiento/$i,2)}}</th>
        <th>{{round($tefectividad/$i,2)}}</th>
    </tr>
</table>

<table>
    <thead>
    <tr>
        <th colspan="4" style="background-color: #93B6E1" align="center">NOVEDADES</th>
    </tr>
    <tr>
        <th style="background-color: #D9D9D9">EJECUTIVO_CEX</th>
        <th style="background-color: #FFFFD5">MOTIVO</th>
        <th style="background-color: #B496D5">COORDINADOR</th>
        <th style="background-color: #D9D9D9">OBSERVACIONES</th>
    </tr>
    </thead>
    <tbody>
    <?php $i=1;?>
    @foreach($reportes5 as $k)
        <tr>
            <td>{{$k['EJECUTIVO_CEX']}}</td>
            <td>{{$k['MOTIVO']}}</td>
            <td>{{$k['COORDINADOR']}}</td>
            <td></td>
            <?php $i++;?>
        </tr>
    @endforeach
    </tbody>
</table>

<table>
    <thead>
    <tr>
        <th style="background-color: #FFFF00">MARCA</th>
        <th style="background-color: #B8CCE4">EJECUTIVO_CEX</th>
        <th style="background-color: #F2DCDB">VISITAS_ASIGNADAS</th>
        <th style="background-color: #B8CCE4">VISITAS_REALIZADAS</th>
        <th style="background-color: #A6A6A6">VISITAS_NO_REALIZADAS</th>
        <th style="background-color: #DB9B99">CUMPLIMIENTO %</th>
        <th style="background-color: #C4BD97">CONTACTO DIRECTO</th>
        <th style="background-color: #C4BD97">CONTACTO INDIRECTO</th>
        <th style="background-color: #C4BD97">NO CONTACTADO</th>
        <th style="background-color: #C4BD97">EFECTIVIDAD %</th>
    </tr>
    </thead>
    <tbody>
    <?php $i=1;?>
    </tbody>
    @foreach($reportes2 as $k)
        <tr>
            <td>{{$k['MARCA']}}</td>
            <td>{{$k['EJECUTIVO_CEX']}}</td>
            <td>{{$k['VISITAS_ASIGNADAS']}}</td>
            <td>{{$k['VISITAS_REALIZADAS']}}</td>
            <td>{{$k['VISITAS_NO_REALIZADAS']}}</td>
            <td>{{$k['CUMPLIMIENTO']}}</td>
            <td>{{$k['CONTACTO_DIRECTO']}}</td>
            <td>{{$k['CONTACTO_INDIRECTO']}}</td>
            <td>{{$k['NO_CONTACTADO']}}</td>
            <td>{{$k['EFECTIVIDAD']}}</td>
            <td></td>
            <?php $i++;?>
        </tr>
    @endforeach
    <tr>
        <td></tbody></td>
    </tr>
</table>

<table>
    <thead>
    <tr>

        <th style="background-color: #B496D5">COORDINADOR</th>
        <th style="background-color: #D9D9D9">EJECUTIVO_CEX</th>
        <th style="background-color: #F2DCDB">VISITAS_ASIGNADAS</th>
        <th style="background-color: #B8CCE4">VISITAS_REALIZADAS</th>
        <th style="background-color: #B8CCE4">VISITAS_NO_REALIZADAS</th>
        <th style="background-color: #DB9B99">CUMPLIMIENTO %</th>
        <th style="background-color: #C4BD97">EFECTIVIDAD %</th>
        <th style="background-color: #CCCCCC">JUSTIFICACION</th>
        <th style="background-color: #CCCCCC">OBSERVACION</th>
    </tr>
    </thead>
    <tbody>
    <?php $i=1;?>
    @foreach($reportes1 as $k)
        <tr>

            <td>{{$k['COORDINADOR']}}</td>
            <td>{{$k['EJECUTIVO_CEX']}}</td>
            <td>{{$k['VISITAS_ASIGNADAS']}}</td>
            <td>{{$k['VISITAS_REALIZADAS']}}</td>
            <td>{{$k['VISITAS_NO_REALIZADAS']}}</td>
            <td>{{$k['CUMPLIMIENTO']}}</td>
            <td>{{$k['EFECTIVIDAD']}}</td>
            <td></td>
            <td></td><?php $i++;?>
        </tr>
    @endforeach
    </tbody>
</table>

