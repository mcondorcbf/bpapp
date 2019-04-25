<table border="1">
    <thead>
    <tr>
        <th style="background-color: #d5d5d5" colspan="10" align="center">REPORTE DE GESTION</th>
    </tr>
    <tr>
        <th>EMPRESA</th>
        <th>AGENCIA</th>
        <th>N° OP ASIGNADOS</th>
        <th style="background-color: #FFF93A">N° OP GESTIONADAS</th>
        <th>TOTAL GESTIONES REALIZADAS</th>
        <th>% BARRIDO</th>
        <th style="background-color: #FFF93A">CONTACTO DIRECTO</th>
        <th style="background-color: #FFF93A">CONTACTO INDIRECTO</th>
        <th style="background-color: #FFF93A">SIN CONTACTO</th>
        <th>% CONTACTADO</th>
    </tr>
    </thead>
    <tbody>
    @foreach($reportes2 as $k)
        <tr>
            <td>{{$k['EMPRESA']}}</td>
            <td>{{$k['AGENCIA']}}</td>
            <td>{{$k['N_OP_ASIGNADOS']}}</td>
            <td>{{$k['N_OP_GESTIONADAS']}}</td>
            <td>{{$k['TOTAL_GESTIONES_REALIZADAS']}}</td>
            <td>{{$k['BARRIDO']}}</td>
            <td>{{$k['CONTACTO_DIRECTO']}}</td>
            <td>{{$k['CONTACTO_INDIRECTO']}}</td>
            <td>{{$k['SIN_CONTACTO']}}</td>
            <td>{{$k['CONTACTADO']}}</td>
        </tr>
    @endforeach
    </tbody>
</table>