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
    <?php $cabecera='';$fila='';$sfila='';$no_cuentas=0;$asignacion=0;$pendiente=0;$recuperacion=0;$porcentaje_recuperacion=0;$brecha=0; $cabecera=$reportes[0]['gestores']; $aRecuperacion=Array(); $brechaComparar=Array(); $brechaMax=Array(); $i=0;?>

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
                $brecha=round(($aRecuperacion[$i]['recuperacion']/$aRecuperacion[$i]['asignacion'])*100,2);
                $aRecuperacion[$i]['brechatotal']=$brecha;

                $brechaMax[$i]=$brecha;
                $brechaComparar[$i]['brecha']=$brecha;
                $brechaComparar[$i]['posicion']=$i;

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
            $aRecuperacion[$i]['brechatotal']='';

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
        $brecha=round(($aRecuperacion[$i]['recuperacion']/$aRecuperacion[$i]['asignacion'])*100,2);
        $aRecuperacion[$i]['brechatotal']=$brecha;

        $brechaMax[$i]=$brecha;
        $brechaComparar[$i]['brecha']=$brecha;
        $brechaComparar[$i]['posicion']=$i;

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
    ?>
    {{-- calculos --}}
    <?php $brechaMax=max($brechaMax); $brechaComparada=Array(); $brechaMaxC=Array();?>
    @foreach($brechaComparar as $k)
        <?php
        $brechaMaxC[$k['posicion']]=round(($brechaMax-$k['brecha']),2);
        $brechaComparada[$k['posicion']]['brechaComparada']=round($brechaMax-$k['brecha'],2);
        $brechaComparada[$k['posicion']]['posicion']=$k['posicion'];
        ?>
        <br>
    @endforeach
    {{-- fin calculos --}}

    {{-- dibuja tabla --}}
    <?php $i=0; $totalCuentas=0; $totalAsignacion=0; $totalPendiente=0; $totalRecuperacion=0;?>
    @foreach($aRecuperacion as $k)
        <?php
            if ($k['brecha']=='st'){
                $totalCuentas=$totalCuentas+$k['no_cuentas'];
                $totalAsignacion=$totalAsignacion+$k['asignacion'];
                $totalPendiente=$totalPendiente+$k['pendiente'];
                $totalRecuperacion=$totalRecuperacion+$k['recuperacion'];
                $fila="<tr>
                <th>".$k['gestores']."</th>
                <th>".$k['region']."</th>
                <th>".$k['no_cuentas']."</th>
                <th>".$k['asignacion']."</th>
                <th>".$k['pendiente']."</th>
                <th>".$k['recuperacion']."</th>";
                if($k['porcentRecuperacion']==$brechaMax){
                    $fila.="<th style='background-color:#37D800'>" .$k['porcentRecuperacion']."</th>";
                }else{
                    $fila.="<th>" .$k['porcentRecuperacion']."</th>";
                }

                if($i==array_search(max($brechaMaxC),$brechaMaxC)){
                    $fila.= "<th style='background-color: #ff1e00'>" .$brechaComparada[$i]['brechaComparada']."</th>
                </tr>";
                }else{
                    $fila.="<th>".$brechaComparada[$i]['brechaComparada']."</th>
                </tr>";
                }
            }else{
                $fila="<tr>
                <td>".$k['gestores']."</td>
                <td>".$k['region']."</td>
                <td>".$k['no_cuentas']."</td>
                <td>".$k['asignacion']."</td>
                <td>".$k['pendiente']."</td>
                <td>".$k['recuperacion']."</td>
                <td>".$k['porcentRecuperacion']."</td>
                <td>".$k['brechatotal']."</td>
                </tr>";
            }
        echo $fila;
            $i++;
        ?>
    @endforeach
    <?php echo '<tr>
                <th style="background-color: #8ed4ff">TOTAL GENERAL</th>
                <th style="background-color: #8ed4ff"></th>
                <th style="background-color: #8ed4ff">'.$totalCuentas.'</th>
                <th style="background-color: #8ed4ff">'.$totalAsignacion.'</th>
                <th style="background-color: #8ed4ff">'.$totalPendiente.'</th>
                <th style="background-color: #8ed4ff">'.$totalRecuperacion.'</th>
                <th style="background-color: #8ed4ff"></th><th style="background-color: #8ed4ff"></th></tr>'; ?>
    {{-- fin dibuja tabla --}}
</table>






