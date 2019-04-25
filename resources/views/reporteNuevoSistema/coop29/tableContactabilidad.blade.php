<table border="1">
    <thead>
    <tr>
        <th style="background-color: #d5d5d5" colspan="3" align="center">TOTAL ASIGNADO</th>
    </tr>
    <tr>
        <th>TIPO DE CONTACTO</th>
        <th>GESTIONADAS</th>
        <th>%</th>
    </tr>
    </thead>
    <tbody>
    <?php $gestionadas=0;$porcentaje=0; ?>
    @foreach($reportes4 as $k)
        <tr>
            <td>{{$k['TIPO_DE_CONTACTO']}}</td>
            <td>{{$k['GESTIONADAS']}}</td>
            <td>{{$k['PORCENT']}}</td>
            <?php
                $gestionadas=$gestionadas+$k['GESTIONADAS'];
                $porcentaje=$porcentaje+$k['PORCENT'];
            ?>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th>Total general</th>
            <th>{{$gestionadas}}</th>
            <th>{{$porcentaje}}</th>
        </tr>
    </tfoot>
</table>

<table border="1">
    <thead>
    <tr>
        <th style="background-color: #d5d5d5" colspan="3" align="center">POR AGENCIAS</th>
    </tr>
    <tr>
        <th>TIPO DE CONTACTO</th>
        <th>GESTIONADAS</th>
        <th>%</th>
    </tr>
    </thead>
    <tbody>
    <?php $h=0;$i=0;$j=0;$cont=0;$tipo=''; $gestionadast=0; $porcentajet=0;?>
    @foreach($reportes3 as $k)
        @if ($tipo!=$k['TIPO_DE_CONTACTO'])
                <tr>
                    <th style="background-color: #d5d5d5" align="center">{{$tipo}}</th>
                    <th style="background-color: #d5d5d5" align="center">{{$gestionadast}}</th>
                    <th style="background-color: #d5d5d5" align="center">{{$porcentajet}}</th>
                    <?php $h++; $tipo=$k['TIPO_DE_CONTACTO']; $gestionadast=0; $porcentajet=0;?>
                </tr>
        @endif
        <tr>
            <td>{{$k['AGENCIA']}}</td>
            <td>{{$k['GESTIONADAS']}}</td>
            <td>{{$k['PORCENT']}}</td>
        </tr>

        <?php $i++;$h=0; $tipo=$k['TIPO_DE_CONTACTO']; $gestionadast=$gestionadast+$k['GESTIONADAS']; $porcentajet=$porcentajet+$k['PORCENT'];?>
        @if ($loop->last)
            <tr>
                <th style="background-color: #d5d5d5" align="center">{{$k['TIPO_DE_CONTACTO']}}</th>
                <th style="background-color: #d5d5d5" align="center">{{$gestionadast}}</th>
                <th style="background-color: #d5d5d5" align="center">{{$porcentajet}}</th>
            </tr>
        @endif
    @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th style="background-color: #d5d5d5">Total general</th>
            <th style="background-color: #d5d5d5">{{$gestionadas}}</th>
            <th style="background-color: #d5d5d5">{{$porcentaje}}</th>
        </tr>
    </tfoot>
</table>