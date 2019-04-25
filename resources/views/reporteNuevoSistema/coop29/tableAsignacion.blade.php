<table border="1">
    <thead>
    <tr>
        <th style="background-color: #d5d5d5" colspan="6" align="center">ASIGNACION FLUJO</th>
    </tr>
    <tr>
        <th style="color:#fff;background-color: #9d2b0b">FECHA</th>
        <th style="color:#fff;background-color: #9d2b0b">EMPRESA</th>
        <th style="color:#fff;background-color: #9d2b0b">NUMERO CITAS</th>
        <th style="color:#fff;background-color: #9d2b0b">VALOR A COBRAR</th>
        <th style="color:#fff;background-color: #9d2b0b">VALOR RECUPERADO</th>
        <th style="color:#fff;background-color: #9d2b0b">ACUMULADO</th>
    </tr>
    </thead>
    <tbody>
    <?php $ncitas=0;$vcobrar=0;$vrecuperado=0;?>
    @foreach($reportes5 as $k)
    <tr>
        <td>{{$k['FECHA']}}</td>
        <td>{{$k['EMPRESA']}}</td>
        <td>{{$k['NUMERO_CTA']}}</td>
        <td>{{$k['VALOR_A_COBRAR']}}</td>
        <td>{{$k['VALOR_RECUPERADO']}}</td>
        <?php $ncitas=$ncitas+$k['NUMERO_CTA']; $vcobrar=$vcobrar+$k['VALOR_A_COBRAR']; $vrecuperado=$vrecuperado+$k['VALOR_RECUPERADO'];?>
        <td>{{$vrecuperado}}</td>
    </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <th>TOTAL</th>
        <th>COBEFEC</th>
        <th>{{$ncitas}}</th>
        <th>{{$vcobrar}}</th>
        <th>{{$vrecuperado}}</th>
        <th></th>
    </tr>
    </tfoot>
</table>