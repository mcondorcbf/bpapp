<table border="1">
    <thead>
    <tr>
        <th style="background-color: #8ed4ff">Gestores</th>
        <th style="background-color: #8ed4ff">Region</th>
        <th style="background-color: #8ed4ff">No. Cuentas</th>
        <th style="background-color: #8ed4ff">Asignacion</th>
        <th style="background-color: #8ed4ff">Pendiente</th>
        <th style="background-color: #8ed4ff">Recuperacion</th>
        <th style="background-color: #8ed4ff">% Recuperacion</th>
        <th style="background-color: #8ed4ff">BRECHA</th>
    </tr>
    </thead>
    <tbody>
    </tbody>
    <?php $cabecera='';$fila='';$sfila='';$no_cuentas=0;$asignacion=0;$pendiente=0;$recuperacion=0;$porcentaje_recuperacion=0;$brecha=0; $cabecera=$reportes[0]['gestores']; $aRecuperacion=Array(); $i=0;?>

    @foreach($reportes as $k)
        @if($cabecera==$k['gestores'])
            <?php
                $sfila='';
            ?>
        @else
            <?php
                $aRecuperacion[$i]['gestores']=$cabecera;
                $aRecuperacion[$i]['region']='';
                $aRecuperacion[$i]['no_cuentas']=$no_cuentas;
                $aRecuperacion[$i]['asignacion']=$asignacion;
                $aRecuperacion[$i]['pendiente']=$pendiente;
                $aRecuperacion[$i]['recuperacion']=$recuperacion;
                $aRecuperacion[$i]['porcentRecuperacion']=$porcentRecuperacion;
                $aRecuperacion[$i]['brecha']='st';
                $sfila="<tr>
                <th colspan='2'>".$cabecera."</th>
                <th>".$no_cuentas."</td>
                <th>".$asignacion."</td>
                <th>".$pendiente."</td>
                <th>".$recuperacion."</td>
                <th>".$porcentRecuperacion."</td>
                <th>".$k['brecha']."</td>
                </tr>";

            $no_cuentas=0;
            $asignacion=0;
            $pendiente=0;
            $recuperacion=0;
            $porcentRecuperacion=0;
            $i++;
            ?>
        @endif
        <?php
            //array_push($aRecuperacion,$sfila);
            //echo $sfila;
            $no_cuentas=$no_cuentas+$k['no_cuentas'];
            $asignacion=$asignacion+$k['asignacion'];
            $pendiente=$pendiente+$k['pendiente'];
            $recuperacion=$recuperacion+$k['recuperacion'];
            $porcentRecuperacion=round(($recuperacion/$asignacion)*100,2);

            $aRecuperacion[$i]['gestores']=$k['gestores'];
            $aRecuperacion[$i]['region']=$k['region'];
            $aRecuperacion[$i]['no_cuentas']=$k['no_cuentas'];
            $aRecuperacion[$i]['asignacion']=$k['asignacion'];
            $aRecuperacion[$i]['pendiente']=$k['pendiente'];
            $aRecuperacion[$i]['recuperacion']=$k['recuperacion'];
            $aRecuperacion[$i]['porcentRecuperacion']=$k['porcentaje_recuperacion'];
            $aRecuperacion[$i]['brecha']=$k['brecha'];

            $fila="<tr>
                <td>".$k['gestores']."</td>
                <td>".$k['region']."</td>
                <td>".$k['no_cuentas']."</td>
                <td>".$k['asignacion']."</td>
                <td>".$k['pendiente']."</td>
                <td>".$k['recuperacion']."</td>
                <td>".$k['porcentaje_recuperacion']."</td>
                <td>".$k['brecha']."</td>
                </tr>";

            $cabecera=$k['gestores'];
            //array_push($aRecuperacion,$fila);
            //echo $fila;
            $i++;
        ?>

    @endforeach
    <?php
        $aRecuperacion[$i]['gestores']=$cabecera;
        $aRecuperacion[$i]['region']='';
        $aRecuperacion[$i]['no_cuentas']=$no_cuentas;
        $aRecuperacion[$i]['asignacion']=$asignacion;
        $aRecuperacion[$i]['pendiente']=$pendiente;
        $aRecuperacion[$i]['recuperacion']=$recuperacion;
        $aRecuperacion[$i]['porcentRecuperacion']=$porcentRecuperacion;
        $aRecuperacion[$i]['brecha']='st';
        $sfila="<tr>
            <th colspan='2'>".$cabecera."</th>
            <th>".$no_cuentas."</th>
            <th>".$asignacion."</th>
            <th>".$pendiente."</th>
            <th>".$recuperacion."</th>
            <th>".$porcentRecuperacion."</th>
            <th></th>
            </tr>";
        //array_push($aRecuperacion,$sfila);
        //echo $sfila;

    var_dump($aRecuperacion);?>
    @foreach($aRecuperacion as $k)
        <?php
            if ($k['brecha']=='st'){
                $fila="<tr>
                <th>".$k['gestores']."</th>
                <th>".$k['region']."</th>
                <th>".$k['no_cuentas']."</th>
                <th>".$k['asignacion']."</th>
                <th>".$k['pendiente']."</th>
                <th>".$k['recuperacion']."</th>
                <th>".$k['porcentRecuperacion']."</th>
                <th>".$k['brecha']."</th>
                </tr>";
            }else{
                $fila="<tr>
                <td>".$k['gestores']."</td>
                <td>".$k['region']."</td>
                <td>".$k['no_cuentas']."</td>
                <td>".$k['asignacion']."</td>
                <td>".$k['pendiente']."</td>
                <td>".$k['recuperacion']."</td>
                <td>".$k['porcentRecuperacion']."</td>
                <td>".$k['brecha']."</td>
                </tr>";
            }
        echo $fila;
        ?>
    @endforeach
</table>