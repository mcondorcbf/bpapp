<table>
    <thead>
    <tr>
        <th>ABONOS</th>
        <th>CAMPANA_COBEFEC</th>
        <th>CAPITAL</th>
        <th>CASTIGO</th>
        <th>CEDULA</th>
        <th>CEX_MEJOR_GESTION_ACCION</th>
        <th>CEX_MEJOR_GESTION_FECHA</th>
        <th>CEX_MEJOR_GESTION_TIPO</th>
        <th>CEX_MEJOR_GESTION_DESCRIPCION</th>
        <th>CEX_MEJOR_GESTION_SUBACCION</th>
        <th>CEX_MEJOR_GESTION_PESO</th>
        <th>CICLO</th>
        <th>CIUDAD</th>
        <th>CUENTA</th>
        <th>AGENTE_ACTUAL</th>
        <th>GESTIONES_CEX</th>
        <th>DIRECCION</th>
        <th>INTERES</th>
        <th>TLC_ULTIMA_GESTION_ACCION</th>
        <th>TLC_ULTIMA_GESTION_TIPO</th>
        <th>TLC_ULTIMA_GESTION_FECHA</th>
        <th>TLC_ULTIMA_GESTION_DESCRIPCION</th>
        <th>TLC_ULTIMA_GESTION_TELEFONO</th>
        <th>TLC_ULTIMA_GESTION_SUBACCION</th>
        <th>TLC_ULTIMA_GESTION_HORA</th>
        <th>TLC_ULTIMA_GESTION_PESO</th>
        <th>TLC_MEJOR_GESTION_ACCION</th>
        <th>TLC_MEJOR_GESTION_TIPO</th>
        <th>TLC_MEJOR_GESTION_FECHA</th>
        <th>TLC_MEJOR_GESTION_DESCRIPCION</th>
        <th>TLC_MEJOR_GESTION_SUBACCION</th>
        <th>TLC_MEJOR_GESTION_PESO</th>
        <th>MARCA</th>
        <th>MINIMO_O_CUOTA_FACTURADA_ORIGINAL</th>
        <th>NOMBRE</th>
        <th>PP_MONTO</th>
        <th>PP_FECHA</th>
        <th>RANGO_DIAS</th>
        <th>SALDO_ACTUAL</th>
        <th>RECUPERACION_MES</th>
        <th>REGION</th>
        <th>RIESGO</th>
        <th>VALOR_TRASLADO</th>
        <th>ZONA</th>
        <th>COBERTURA</th>
    </tr>
    </thead>
    <tbody>
    @foreach($reportes as $reporte)
    <tr>
        <td>{{$reporte['ABONOS']}}</td>
        <td>{{$reporte['CAMPANA_COBEFEC']}}</td>
        <td>{{$reporte['CAPITAL']}}</td>
        <td>{{$reporte['CASTIGO']}}</td>
        <td>{{$reporte['CEDULA']}}</td>
        <td>{{$reporte['CEX_MEJOR_GESTION_ACCION']}}</td>
        <td>{{$reporte['CEX_MEJOR_GESTION_FECHA']}}</td>
        <td>{{$reporte['CEX_MEJOR_GESTION_TIPO']}}</td>
        <td>{{$reporte['CEX_MEJOR_GESTION_DESCRIPCION']}}</td>
        <td>{{$reporte['CEX_MEJOR_GESTION_SUBACCION']}}</td>
        <td>{{$reporte['CEX_MEJOR_GESTION_PESO']}}</td>
        <td>{{$reporte['CICLO']}}</td>
        <td>{{$reporte['CIUDAD']}}</td>
        <td>{{$reporte['CUENTA']}}</td>
        <td>{{$reporte['AGENTE_ACTUAL']}}</td>
        <td>{{$reporte['GESTIONES_CEX']}}</td>
        <td>{{$reporte['DIRECCION']}}</td>
        <td>{{$reporte['INTERES']}}</td>
        <td>{{$reporte['TLC_ULTIMA_GESTION_ACCION']}}</td>
        <td>{{$reporte['TLC_ULTIMA_GESTION_TIPO']}}</td>
        <td>{{$reporte['TLC_ULTIMA_GESTION_FECHA']}}</td>
        <td>{{$reporte['TLC_ULTIMA_GESTION_DESCRIPCION']}}</td>
        <td>{{$reporte['TLC_ULTIMA_GESTION_TELEFONO']}}</td>
        <td>{{$reporte['TLC_ULTIMA_GESTION_SUBACCION']}}</td>
        <td>{{$reporte['TLC_ULTIMA_GESTION_HORA']}}</td>
        <td>{{$reporte['TLC_ULTIMA_GESTION_PESO']}}</td>
        <td>{{$reporte['TLC_MEJOR_GESTION_ACCION']}}</td>
        <td>{{$reporte['TLC_MEJOR_GESTION_TIPO']}}</td>
        <td>{{$reporte['TLC_MEJOR_GESTION_FECHA']}}</td>
        <td>{{$reporte['TLC_MEJOR_GESTION_DESCRIPCION']}}</td>
        <td>{{$reporte['TLC_MEJOR_GESTION_SUBACCION']}}</td>
        <td>{{$reporte['TLC_MEJOR_GESTION_PESO']}}</td>
        <td>{{$reporte['MARCA']}}</td>
        <td>{{$reporte['MINIMO_O_CUOTA_FACTURADA_ORIGINAL']}}</td>
        <td>{{$reporte['NOMBRE']}}</td>
        <td>{{$reporte['PP_MONTO']}}</td>
        <td>{{$reporte['PP_FECHA']}}</td>
        <td>{{$reporte['RANGO_DIAS']}}</td>
        <td>{{$reporte['SALDO_ACTUAL']}}</td>
        <td>{{$reporte['RECUPERACION_MES']}}</td>
        <td>{{$reporte['REGION']}}</td>
        <td>{{$reporte['RIESGO']}}</td>
        <td>{{$reporte['VALOR_TRASLADO']}}</td>
        <td>{{$reporte['ZONA']}}</td>
        <td>{{$reporte['COBERTURA']}}</td>
    </tr>
    @endforeach
    </tbody>
</table>