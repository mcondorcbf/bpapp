<table>
    <thead>
    <tr>
        <th>Gestion</th>
        <th>Fecha</th>
        <th>Hora</th>
        <th>Ci</th>
        <th>Nombres</th>
        <th>Telefono</th>
        <th>Estatus</th>
        <th>Gestion</th>
        <th>Observacion</th>
        <th>Fecha_pp</th>
        <th>Valor</th>
        <th>Campana</th>
        <th>Sistema</th>
    </tr>
    </thead>
    <tbody>
    @foreach($reportes as $reporte)
    <tr>
        <td>{{$reporte['contadorGestion']}}</td>
        <td>{{$reporte['fecha']}}</td>
        <td>{{$reporte['hora']}}</td>
        <td>{{$reporte['ci']}}</td>
        <td>{{$reporte['nombre']}}</td>
        <td>{{$reporte['telefono']}}</td>
        <td>{{$reporte['status']}}</td>
        <td>{{$reporte['gestion']}}</td>
        <td>{{$reporte['observacion']}}</td>
        <td>{{$reporte['fecha_pp']}}</td>
        <td>{{$reporte['valor_pp']}}</td>
        <td>{{$reporte['campana']}}</td>
        <td>{{$reporte['servidor']}}</td>
    </tr>
    @endforeach
    </tbody>
</table>