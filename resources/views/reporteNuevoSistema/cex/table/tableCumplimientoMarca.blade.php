<table border="1">
    <thead>
    <tr>
        <th style="background-color: #FFFF00">MARCA</th>
        <th style="background-color: #B8CCE4">FECHA DE CARGA</th>
        <th style="background-color: #F2DCDB">VISITAS_ASIGNADAS</th>
        <th style="background-color: #B8CCE4">VISITAS_REALIZADAS</th>
        <th style="background-color: #A6A6A6">VISITAS_NO_REALIZADAS</th>
        <th style="background-color: #DB9B99">CUMPLIMIENTO %</th>
        <th style="background-color: #FFFFCC">EFECTIVIDAD %</th>
    </tr>
    </thead>
    <tbody>
    @foreach($reportes3 as $k)
        <tr>
            <td>{{$k['MARCA']}}</td>
            <td>{{$fecha_inicio}}</td>
            <td>{{$k['VISITAS_ASIGNADAS']}}</td>
            <td>{{$k['VISITAS_REALIZADAS']}}</td>
            <td>{{$k['VISITAS_NO_REALIZADAS']}}</td>
            <td>{{$k['CUMPLIMIENTO']}}</td>
            <td>{{$k['EFECTIVIDAD']}}</td>
        </tr>
        <tr style="background-color: #D9D9D9">
            <th></th>
            <th>TOTAL</th>
            <th>{{$k['VISITAS_ASIGNADAS']}}</th>
            <th>{{$k['VISITAS_REALIZADAS']}}</th>
            <th>{{$k['VISITAS_NO_REALIZADAS']}}</th>
            <th>{{$k['CUMPLIMIENTO']}}</th>
            <th>{{$k['EFECTIVIDAD']}}</th>
        </tr>
    @endforeach
    </tbody>
</table>

<table border="1">
    <thead>
    <tr>
        <th colspan="14" style="background-color: #DB9B99" align="center">VISITAS POR HORA</th>
    </tr>
    <tr style="background-color: #B8CCE4">
        <th>EJECUTIVO CEX</th>
        <th>NO REALIZADAS</th>
        <th><9</th>
        <th>9 a 10</th>
        <th>10 a 11</th>
        <th>11 a 12</th>
        <th>12 a 13</th>
        <th>13 a 14</th>
        <th>14 a 15</th>
        <th>15 a 16</th>
        <th>16 a 17</th>
        <th>17 a 18</th>
        <th>>18</th>
        <th style="background-color: #DB9B99">TOTAL DE VISITAS</th>
    </tr>
    </thead>
    <tbody>
    <?php $tno_realizadas=0;$tmenor9=0;$t9=0;$t10=0;$t11=0;$t12=0;$t13=0;$t14=0;$t15=0;$t16=0;$t17=0;$tm18=0;$ttotal_visitas=0;?>
    @foreach($reportes9 as $k)
        <tr>
            <td style="background-color: #D9D9D9">{{$k['ejecutivo_cex']}}</td>
            <td>{{$k['no_realizadas']}}</td><?php $tno_realizadas=$tno_realizadas+$k['no_realizadas']; ?>
            <td>{{$k['<9']}}</td><?php $tmenor9=$tmenor9+$k['<9']; ?>
            <td>{{$k['9']}}</td><?php $t9=$t9+$k['9']; ?>
            <td>{{$k['10']}}</td><?php $t10=$t10+$k['10']; ?>
            <td>{{$k['11']}}</td><?php $t11=$t11+$k['11']; ?>
            <td>{{$k['12']}}</td><?php $t12=$t12+$k['12']; ?>
            <td>{{$k['13']}}</td><?php $t13=$t13+$k['13']; ?>
            <td>{{$k['14']}}</td><?php $t14=$t14+$k['14']; ?>
            <td>{{$k['15']}}</td><?php $t15=$t15+$k['15']; ?>
            <td>{{$k['16']}}</td><?php $t16=$t16+$k['16']; ?>
            <td>{{$k['17']}}</td><?php $t17=$t17+$k['17']; ?>
            <td>{{$k['>=18']}}</td><?php $tm18=$tm18+$k['>=18']; ?>
            <td>{{$k['total_visitas']}}</td><?php $ttotal_visitas=$ttotal_visitas+$k['total_visitas']; ?>
        </tr>
    @endforeach
    <tr style="background-color: #D9D9D9">
        <th >TOTAL</th>
        <th>{{$tno_realizadas}}</th>
        <th>{{$tmenor9}}</th>
        <th>{{$t9}}</th>
        <th>{{$t10}}</th>
        <th>{{$t11}}</th>
        <th>{{$t12}}</th>
        <th>{{$t13}}</th>
        <th>{{$t14}}</th>
        <th>{{$t15}}</th>
        <th>{{$t16}}</th>
        <th>{{$t17}}</th>
        <th>{{$tm18}}</th>
        <th>{{$ttotal_visitas}}</th>
    </tr>
    </tbody>
</table>

<table border="1">
    <thead>
    <tr>
        <th colspan="9" style="background-color: #DB9B99" align="center">VISITAS POR EJECUTIVO</th>
    </tr>
    <tr>
        <th style="background-color: #A6A6A6">EJECUTIVO_CEX</th>
        <th style="background-color: #F2DCDB">VISITAS_ASIGNADAS</th>
        <th style="background-color: #B8CCE4">VISITAS_REALIZADAS</th>
        <th style="background-color: #B8CCE4">VISITAS_NO_REALIZADAS</th>
        <th style="background-color: #DB9B99">CUMPLIMIENTO %</th>
        <th style="background-color: #C4BD97">CONTACTO DIRECTO</th>
        <th style="background-color: #C4BD97">CONTACTO INDIRECTO</th>
        <th style="background-color: #C4BD97">NO CONTACTADO</th>
        <th style="background-color: #C4BD97">EFECTIVIDAD %</th>
    </tr>
    </thead>
    <tbody>
    <?php $tvasignadas=0;$tvrealizadas=0;$tvno_realizadas=0;$tcumplimiento=0;$tc_directo=0;$tc_indirecto=0;$tnocontactado=0;$tefectividad=0;$i=0;?>
    @foreach($reportes2 as $k)
        <tr>
            <td>{{$k['EJECUTIVO_CEX']}}</td>
            <td>{{$k['VISITAS_ASIGNADAS']}}</td><?php $tvasignadas=$tvasignadas+$k['VISITAS_ASIGNADAS']; ?>
            <td>{{$k['VISITAS_REALIZADAS']}}</td><?php $tvrealizadas=$tvrealizadas+$k['VISITAS_REALIZADAS']; ?>
            <td>{{$k['VISITAS_NO_REALIZADAS']}}</td><?php $tvno_realizadas=$tvno_realizadas+$k['VISITAS_NO_REALIZADAS']; ?>
            <td>{{$k['CUMPLIMIENTO']}}</td><?php $tcumplimiento=$tcumplimiento+$k['CUMPLIMIENTO']; ?>
            <td>{{$k['CONTACTO_DIRECTO']}}</td><?php $tc_directo=$tc_directo+$k['CONTACTO_DIRECTO']; ?>
            <td>{{$k['CONTACTO_INDIRECTO']}}</td><?php $tc_indirecto=$tc_indirecto+$k['CONTACTO_INDIRECTO']; ?>
            <td>{{$k['NO_CONTACTADO']}}</td><?php $tnocontactado=$tnocontactado+$k['NO_CONTACTADO']; ?>
            <td>{{$k['EFECTIVIDAD']}}</td><?php $tefectividad=$tefectividad+$k['EFECTIVIDAD']; $i++?>
        </tr>
    @endforeach
    <tr style="background-color: #D9D9D9">
        <th></th>
        <th>{{$tvasignadas}}</th>
        <th>{{$tvrealizadas}}</th>
        <th>{{$tvno_realizadas}}</th>
        <th>{{$tcumplimiento/$i}}</th>
        <th>{{$tc_directo}}</th>
        <th>{{$tc_indirecto}}</th>
        <th>{{$tnocontactado}}</th>
        <th>{{$tefectividad/$i}}</th>
    </tr>
    </tbody>
</table>

<table border="1">
    <thead>
    <tr>
        <th colspan="4" style="background-color: #DB9B99" align="center">VISITAS POR ACCION</th>
    </tr>
    <tr>
        <th style="background-color: #F2DCDB">STATUS</th>
        <th style="background-color: #B8CCE4"># VISITAS</th>
        <th style="background-color: #B8CCE4">%</th>
        <th style="background-color: #D9D9D9">TIPO</th>
    </tr>
    </thead>
    <tbody>
    <?php $tvisitas=0;?>
    @foreach($reportes7 as $k)
        <tr>
            <td style="background-color: #D9D9D9">{{$k['status']}}</td>
            <td>{{$k['visitas']}}</td><?php $tvisitas=$tvisitas+$k['visitas'];?>
            <td style="background-color: #B8CCE4">{{$k['%']}}</td>
            <td>{{$k['tipo']}}</td>
        </tr>
    @endforeach
    <tr>
        <th></th>
        <th style="background-color: #D9D9D9">{{$tvisitas}}</th>
        <th></th>
        <th></th>
    </tr>
    </tbody>
</table>

<table border="1">
    <thead>
    <tr>
        <th colspan="3" style="background-color: #DB9B99" align="center">VISITAS POR TIPO</th>
    </tr>
    <tr>
        <th style="background-color: #C893AC">TIPO</th>
        <th style="background-color: #C893AC"># VISITAS</th>
        <th style="background-color: #C893AC">%</th>
    </tr>
    </thead>
    <tbody>
    <?php $tvisitas=0;?>
    @foreach($reportes8 as $k)
        <tr>
            <td style="background-color: #CCCCCC">{{$k['tipo']}}</td>
            <td >{{$k['visitas']}}</td><?php $tvisitas=$tvisitas+$k['visitas'];?>
            <td style="background-color: #CCCCCC">{{$k['%']}}</td>
        </tr>
    @endforeach
    <tr>
        <th></th>
        <th style="background-color: #CCCCCC">{{$tvisitas}}</th>
        <th></th>
    </tr>
    </tbody>
</table>

<table border="1">
    <thead>
    <tr>
        <th colspan="4" style="background-color: #1BD82E" align="center">VISITAS REALIZADAS</th>
    </tr>
    <tr style="background-color: #32C7E7" align="center">
        <th>GESTOR</th>
        <th>CODIGO</th>
        <th>NOMBRE</th>
        <th>RESULTADO</th>
    </tr>
    </thead>
    <tbody>
    @foreach($reportes10 as $k)
        <tr>
            <td>{{$k['gestor']}}</td>
            <td>{{$k['codigo']}}</td>
            <td>{{$k['nombre']}}</td>
            <td>{{$k['resultado']}}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<table border="1">
    <thead>
    <tr>
        <th colspan="4" style="background-color: #DFB944" align="center">VISITAS NO REALIZADAS</th>
    </tr>
    <tr style="background-color: #32C7E7" align="center">
        <th>GESTOR</th>
        <th>CODIGO</th>
        <th>NOMBRE</th>
    </tr>
    </thead>
    <tbody>
    @foreach($reportes11 as $k)
        <tr>
            <td>{{$k['gestor']}}</td>
            <td>{{$k['codigo']}}</td>
            <td>{{$k['nombre']}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
