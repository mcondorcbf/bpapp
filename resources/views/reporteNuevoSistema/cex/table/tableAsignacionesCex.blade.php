<table border="1">
    <thead>
    <tr>
        <th style="background-color: #8ed4ff">Fecha</th>
        <th style="background-color: #8ed4ff">Hora</th>
        <th style="background-color: #8ed4ff">Marca</th>
        <th style="background-color: #8ed4ff">Coordinador</th>
        <th style="background-color: #8ed4ff">Gestor_CEX</th>
        <th style="background-color: #8ed4ff">Cedula</th>
        <th style="background-color: #8ed4ff">Nombre</th>
        <th style="background-color: #8ed4ff">Tipo</th>
        <th style="background-color: #8ed4ff">Accion</th>
        <th style="background-color: #8ed4ff">Subaccion</th>
        <th style="background-color: #8ed4ff">Descripcion</th>
        <th style="background-color: #8ed4ff">Direccion</th>
        <th style="background-color: #8ed4ff">Referencia</th>
        <th style="background-color: #8ed4ff">Carta</th>
        <th style="background-color: #8ed4ff">Campana</th>
        <th style="background-color: #8ed4ff">Producto</th>
        <th style="background-color: #8ed4ff">Ciudad</th>
        <th style="background-color: #8ed4ff">Ruta</th>
        <th style="background-color: #8ed4ff">Zona</th>
        <th style="background-color: #8ed4ff">Region</th>
        <th style="background-color: #8ed4ff">Seccion</th>
        <th style="background-color: #8ed4ff">Ciclo</th>
        <th style="background-color: #8ed4ff">Valor_pendiente</th>
        <th style="background-color: #8ed4ff">Estado_cuenta</th>
    </tr>
    </thead>
    <tbody>
    </tbody>
    @foreach($reportes as $k)
        <tr>
            <td>{{$k['Fecha']}}</td>
            <td>{{$k['Hora']}}</td>
            <td>{{$k['Marca']}}</td>
            <td>{{$k['Coordinador']}}</td>
            <td>{{$k['Gestor_CEX']}}</td>
            <td>{{$k['Cedula']}}</td>
            <td>{{$k['Nombre']}}</td>
            <td>{{$k['Tipo']}}</td>
            <td>{{$k['Accion']}}</td>
            <td>{{$k['Subaccion']}}</td>
            <td>{{$k['Descripcion']}}</td>
            <td>{{$k['Direccion']}}</td>
            <td>{{$k['Referencia']}}</td>
            <td>{{$k['Carta']}}</td>
            <td>{{$k['Campana']}}</td>
            <td>{{$k['Producto']}}</td>
            <td>{{$k['Ciudad']}}</td>
            <td>{{$k['Ruta']}}</td>
            <td>{{$k['Zona']}}</td>
            <td>{{$k['Region']}}</td>
            <td>{{$k['Seccion']}}</td>
            <td>{{$k['Ciclo']}}</td>
            <td>{{$k['Valor_pendiente']}}</td>
            <td>{{$k['Estado_cuenta']}}</td>
        </tr>
    @endforeach
</table>