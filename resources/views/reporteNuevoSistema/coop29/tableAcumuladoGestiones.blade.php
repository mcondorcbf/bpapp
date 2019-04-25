<table border="1">
    <thead>
    <tr>
        <th style="color:#fff;background-color: #9C1700">EMPRESA</th>
        <th style="color:#fff;background-color: #9C1700">MES</th>
        <th style="color:#fff;background-color: #9C1700">FECHA DE GESTIÓN</th>
        <th style="color:#fff;background-color: #9C1700">NOMBRES DEUDOR</th>
        <th style="color:#fff;background-color: #9C1700">CEDULA DEUDOR</th>
        <th style="color:#fff;background-color: #9C1700">AGENCIA</th>
        <th style="color:#fff;background-color: #9C1700">N. OPERACIÓN</th>
        <th style="color:#fff;background-color: #9C1700">PRODUCTO</th>
        <th style="color:#fff;background-color: #9C1700">SALDO EN RIESGO</th>
        <th style="color:#fff;background-color: #9C1700">DIAS MORA</th>
        <th style="color:#fff;background-color: #9C1700">ACCION</th>
        <th style="color:#fff;background-color: #9C1700">TIPO DE CONTACTO</th>
        <th style="color:#fff;background-color: #9C1700">RESPUESTA</th>
        <th style="color:#fff;background-color: #9C1700">MOTIVO DE NO PAGO</th>
        <th style="color:#fff;background-color: #9C1700">FECHA DE COMPROMISO DE PAGO</th>
        <th style="color:#fff;background-color: #9C1700">OBSERVACION</th>
    </tr>
    </thead>
    <tbody>
    @foreach($reportes as $k)
        <tr>
            <td>{{$k['EMPRESA']}}</td>
            <td>{{$k['MES']}}</td>
            <td>{{$k['FECHA_GESTION']}}</td>
            <td>{{$k['NOMBRES_DEUDOR']}}</td>
            <td>{{$k['CEDULA_DEUDOR']}}</td>
            <td>{{$k['AGENCIA']}}</td>
            <td>{{(string) $k['N_OPERACION']}}</td>
            <td>{{$k['PRODUCTO']}}</td>
            <td>{{$k['SALDO_EN_RIESGO']}}</td>
            <td>{{$k['DIAS_MORA']}}</td>
            <td>{{$k['ACCION']}}</td>
            <td>{{$k['TIPO_CONTACTO']}}</td>
            <td>{{$k['RESPUESTA']}}</td>
            <td>{{$k['MOTIVO_NO_PAGO']}}</td>
            <td>{{$k['FECHA_COMPROMISO_PAGO']}}</td>
            <td>{{$k['OBSERVACION']}}</td>
        </tr>
    @endforeach
    </tbody>
</table>