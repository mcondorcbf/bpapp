<table border="1">
    <thead>
    <tr>
        <th style="background-color: #ccffcc">TIPO DE GESTION<br>
            1 Gestion Normal<br>
            2  Acuerdo de Pago
        </th>
        <th style="background-color: #ccffcc">Numero de Idetificacion del Cliente</th>
        <th style="background-color: #ccffcc">Casa de Cobranza a la Que pertenece el Usuario que realiza la Gestion</th>
        <th style="background-color: #ccffcc">Usuario SAC<br>
            el usuario que queda con la gestión registrada</th>
        <th style="background-color: #ccffcc">Fecha de realizacion de la Gestion</th>
        <th style="background-color: #ccffcc">Accion Realizada en la Gestion<br>
            HACER LLAMADA<br>
            Para el Caso del Call Center
        </th>
        <th style="background-color: #ccffcc">Respuesta Obtenida en la Gestion<br>
            Tipologia de calificación de la llamada)
        </th>
        <th style="background-color: #ccffcc">Contacto al Cual se Gestiono<br>
            El contacto se califica  de acuerdo con la tipologia de la gestión realizada en la Llamada )
        </th>
        <th style="background-color: #ccffcc">Comentarios de la Gestión Realizada<br>
            Son los comentarios realizados por el asesor al momento de realziar la Gestión de Cobro )
        </th>
        <th style="background-color: #ccffcc">Numero de Telefono en el Cual se Realizo la Gestion<br>
            Incluir numero completo con el cero sin caracteres especiales solo el número
        </th>
        <th style="background-color: #ccffcc">Motivo de No Pago<br>
            Ver Catálogos de Motivos de No Pago
        </th>
        <th style="background-color: #ccffcc">Fecha de proxima gestion en la Cual se Gestionara el cliente<br>
            DD/MM/AAAA/ hhmmss<br>
            Debe ser Mayor que la fecha de Gestión)
        </th>
        <th style="background-color: #ccffcc">Tiempo en que tomo en realizar la Gestion</th>
        <th style="background-color: #ccffcc">Numero de Cuenta en la Cual se Realizo promesa de Pago<br>
            Solo si se realiza un acuerdo de pago)
        </th>
        <th style="background-color: #ccffcc">Valor Promesa<br>
            Valor que va a Pagar el Cliente sin puntos comas o signos especiales
        </th>
        <th style="background-color: #ccffcc">Fecha en la Cual se Compromete a Pagar cliente<br>
            DD/MM/AAAA/ hhmmss
        </th>
        <th style="background-color: #ccffcc">Direccion en la Cual Se Visito<br>
            Solo se diligencia si se realizo una visita al deudor
        </th>
        <th style="background-color: #ccffcc">SUB CAMPANA
            Sub Campna La que registra el Deudor al momento de enviar el archivo a cargar
        </th>
        <th style="background-color: #ccffcc">CANAL DE GESTION<br>
            Por defecto sera Gestión
        </th>
    </tr>
    </thead>
    <tbody>
    @foreach($reportes as $k)
        <tr>
            <td>{{$k['tipo_gestion']}}</td>
            <td>{{$k['numero_identificacion']}}</td>
            <td>{{$k['casa_cobranza']}}</td>
            <td>{{$k['usuario_sac']}}</td>
            <td>{{$k['fecha']}}</td>
            <td>{{$k['accion_realizada']}}</td>
            <td>{{$k['respuesta_obtenida']}}</td>
            <td>{{$k['contacto']}}</td>
            <td>{{$k['comentarios']}}</td>
            <td>{{$k['numero_de_telefono']}}</td>
            <td>{{$k['motivo_no_pago']}}</td>
            <td>{{$k['fecha_proxima_gestion']}}</td>
            <td>{{$k['tiempo']}}</td>
            <td>{{$k['numero_cuenta']}}</td>
            <td>{{$k['valor_promesa']}}</td>
            <td>{{$k['fecha_promesa']}}</td>
            <td>{{$k['direccion_visita']}}</td>
            <td>{{$k['sub_campana']}}</td>
            <td>{{$k['canal_gestion']}}</td>
        </tr>
    @endforeach
    </tbody>
</table>